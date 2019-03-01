# action item detection service
A docker based microservice to provide action and decision item detection service of meetings. 
URL:   https://tfl-action-item-service.azurewebsites.net

## How to use
To use this service you POST a meeting document to it the document is based on our meeting object schema an example of which is

```
{

    "uids": [
        "1",
        "2",
        .....
    ]
}
```

The returned result will look like


```

[
    {
     'uid': "1"
     'tier1': ["CCMC","FONP","PSCB"],
    
    },
    ...
]
```
## Model Training
The underlying model is trained using xgboost.
Input features include the following parts:
````
      1.Number of person and time entities (Spacy)
      2.Part of Speech tags (Number of different types of verbs) (Spacy)
      3.Bag of words (both manually selected and most frequent words in training corpus)
      
