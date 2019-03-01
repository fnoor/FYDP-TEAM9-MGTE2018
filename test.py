#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Thu Feb 28 12:51:53 2019

@author: xinruishao
"""
import config
from sklearn.externals import joblib
import mysql.connector as sql
import numpy as np
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

from preprocessing import prepare_data
id = ['008f13b1e2adb7d50a7ad1f2ac8d6b9fe27d38dfc70452ebf12b42d85422710f']
result = prepare_data(id,db_connection,label_encoder1,label_encoder2,label_encoder4,onehot_encoder)
print(result[1])
y_pred = model.predict(result[1])
y_pred_prob = model.predict_proba(result[1])
a = np.array(y_pred_prob[0])
ind_5 = np.argsort(a)[-5:]
ind_4 = np.argsort(a)[-4:]
ind_3 = np.argsort(a)[-3:]
ind_2 = np.argsort(a)[-2:]
print(label_encoder_y.classes_[ind_5[::-1]])
print([ x for x in label_encoder_y.classes_[ind_4[::-1]] if x not in result[2][id[0]]])
print(label_encoder_y.classes_[ind_3[::-1]])
print(label_encoder_y.classes_[ind_2[::-1]])

print(result[2])

