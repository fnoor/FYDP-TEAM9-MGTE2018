from sklearn.externals import joblib
import mysql.connector as sql
import config
import numpy as np
from preprocessing import prepare_data

def setup():
     global label_encoder1
     global label_encoder2
     global label_encoder4
     global label_encoder_y
     global onehot_encoder
     global model
     global db_connection
     label_encoder1 = joblib.load("models/label_encoder1.joblib.dat")
     label_encoder2 = joblib.load("models/label_encoder2.joblib.dat")
     label_encoder4 = joblib.load("models/label_encoder4.joblib.dat")
     onehot_encoder = joblib.load("models/onehot_encoder.joblib.dat")
     label_encoder_y = joblib.load("models/lab_encoder_y.joblib.dat")
     model = joblib.load("models/xgboost_1_22.joblib.dat")
     db_connection = sql.connect(host=config.DATABASE_CONFIG['host'], 
                                 database=config.DATABASE_CONFIG['dbname'], 
                                 user=config.DATABASE_CONFIG['user'], 
                                 password=config.DATABASE_CONFIG['password'])

class Recommendation:
    
    def __init__(self, user_ids):
        self.user_ids = user_ids
        self.df = prepare_data(self.user_ids,db_connection,label_encoder1,label_encoder2,label_encoder4,onehot_encoder)
        
    def recommend(self):
        result = []
        for idx,uid in enumerate(self.user_ids):
            rec = {}
            rec['uid'] = uid
            y_pred_prob = model.predict_proba(self.df[1])
            a = np.array(y_pred_prob[idx])
            top_6_idx = np.argsort(a)[-6:]
            top = [ x for x in label_encoder_y.classes_[top_6_idx[::-1]] if x not in self.df[2][uid]]
            top3 = top[:3]
            rec['tier1'] = top3
            result.append(rec)
        return result
            
setup()        
     

