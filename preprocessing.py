from sklearn.preprocessing import LabelEncoder
from sklearn.preprocessing import OneHotEncoder
from xgboost import XGBClassifier
import pandas as pd
import numpy as np
from pandas import DataFrame
from pandas import concat
import mysql.connector as sql
import config
statement='select a.user_id_hash, b.course_code,b.course_name,a.activation_date,c.lhin,c.city,d.ed_level,c.province_code,DATEDIFF( c.lastlogin,c.created) AS activeDuration FROM '+config.DATABASE_CONFIG['enroll_table']+' a join '+config.DATABASE_CONFIG['course_table']+' b on a.course_id =b.course_id join '+config.DATABASE_CONFIG['user_table']+' c on a.user_id_hash = c.user_id_hash join '+config.DATABASE_CONFIG['ed_level_table']+' d on c.education_level_id =d.education_level_id where a.user_id_hash= %s && a.status <>3 order by a.activation_date asc;'

def series_to_supervised(data, n_in=1, n_out=1, dropnan=False):
	"""
	Frame a time series as a supervised learning dataset.
	Arguments:
		data: Sequence of observations as a list or NumPy array.
		n_in: Number of lag observations as input (X).
		n_out: Number of observations as output (y).
		dropnan: Boolean whether or not to drop rows with NaN values.
	Returns:
		Pandas DataFrame of series framed for supervised learning.
	"""
	n_vars = 1 if type(data) is list else data.shape[1]
	df = DataFrame(data)
	cols, names = list(), list()
	# input sequence (t-n, ... t-1)
	for i in range(n_in, 0, -1):
		cols.append(df.shift(i))
		names += [('var%d(t-%d)' % (j+1, i)) for j in range(n_vars)]
	# forecast sequence (t, t+1, ... t+n)
	for i in range(0, n_out):
		cols.append(df.shift(-i))
		if i == 0:
			names += [('var%d(t)' % (j+1)) for j in range(n_vars)]
		else:
			names += [('var%d(t+%d)' % (j+1, i)) for j in range(n_vars)]
	# put it all together
	agg = concat(cols, axis=1)
	agg.columns = names
	# drop rows with NaN values
	if dropnan:
		agg.dropna(inplace=True)
	return agg


def prepare_data(ids,db_connection,label_encoder1,label_encoder2,label_encoder4,onehot_encoder):
    df = pd.DataFrame(columns=['user_id_hash','activation_date','lhin','city','ed_level','province_code','activeDuration','var1(t-3)','var1(t-2)','var1(t-1)','var1(t)'])
    user_course={}
    for id in ids:
        df_1 = pd.read_sql(statement, con=db_connection,params=(id,))
        user_course[id] = df_1['course_code'].tolist()
        data = series_to_supervised(df_1[['course_code']],3)
        df_2=df_1.drop(['course_code','course_name'],axis= 1)
        df_3=pd.concat([df_2,data],axis= 1)
        df = pd.concat([df, df_3], ignore_index=True)
    df["city"]=df["city"].map(lambda x: ((x.lower()).replace(" ","")).replace("torotno","toronto"))
    df["province_code"]=df["province_code"].map(lambda x: x.replace("Ontario","ON"))
    df['activeDuration'].fillna((df['activeDuration'].mean()), inplace=True)
    df['var1(t-2)'].fillna('no_course', inplace=True)
    df['var1(t-3)'].fillna('no_course', inplace=True)
    df['var1(t-1)'].fillna('no_course', inplace=True)
    df['lhin'].fillna(1000, inplace=True)
    df.loc[df['var1(t-2)'] == df['var1(t-1)'], 'var1(t-2)'] = 'no_course'
    df.loc[df['var1(t-2)'] == df['var1(t)'], 'var1(t-2)'] = 'no_course'

    df.loc[df['var1(t-3)'] == df['var1(t-2)'], 'var1(t-3)'] = 'no_course'
    df.loc[df['var1(t-3)'] == df['var1(t-1)'], 'var1(t-3)'] = 'no_course'
    df.loc[df['var1(t-3)'] == df['var1(t)'], 'var1(t-3)'] = 'no_course'

    df=df[df['var1(t)'] != df['var1(t-1)']]
    df.loc[(df['var1(t)'] == 'CCMC') & (df['var1(t-1)'] != 'no_course') , 'var1(t)'] = 'no_course'
    df.loc[(df['var1(t)'] == 'CCMC') & (df['var1(t-2)'] != 'no_course') , 'var1(t)'] = 'no_course'
    df.loc[(df['var1(t)'] == 'CCMC') & (df['var1(t-3)'] != 'no_course') , 'var1(t)'] = 'no_course'
    df=df[df['var1(t)'] != 'no_course']
    df=df.drop(['city'],axis=1)
    df=df.drop(['activation_date'],axis= 1)
    
    latest_df=pd.DataFrame(columns=['user_id_hash','lhin','ed_level','province_code','activeDuration','var1(t-3)','var1(t-2)','var1(t-1)','var1(t)'])
    user_id_hash = []
    for i in range(0,df.shape[0]):
        if df.iloc[i]['user_id_hash'] in user_id_hash:
            latest_df.drop(latest_df.tail(1).index,inplace=True)    
        else:
            user_id_hash.append(df.iloc[i]['user_id_hash'])
        latest_df = pd.concat([latest_df, df.iloc[[i]]], ignore_index=True)
    
    latest_df['var1(t-3)'] = latest_df['var1(t-2)']
    latest_df['var1(t-2)'] = latest_df['var1(t-1)']
    latest_df['var1(t-1)'] = latest_df['var1(t)']
    
    ids_list = latest_df['user_id_hash']
    df = latest_df.drop(['user_id_hash'],axis= 1)
    print(df)
    X_exmple=df.values[:,0:7]


    X_exmple[:,1] = label_encoder1.transform(X_exmple[:,1])

    X_exmple[:,2] = label_encoder2.transform(X_exmple[:,2])

    X_exmple[:,4] = label_encoder4.transform(X_exmple[:,4])

    X_exmple[:,5] = label_encoder4.transform(X_exmple[:,5])


    X_exmple[:,6] = label_encoder4.transform(X_exmple[:,6])


    X_exmple=onehot_encoder.transform(X_exmple)
    
    return [ids_list,X_exmple,user_course]
    
    

