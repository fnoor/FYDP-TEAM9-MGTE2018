from flask import Flask, jsonify, request
import os
from waitress import serve
from recommendation import Recommendation

app = Flask(__name__)


@app.route('/', methods=['POST'])
def ner_transcript():
    ids = request.json
    result = Recommendation(ids['uids']).recommend()
    return jsonify(result)

@app.route('/health', methods=['GET'])
def health():
    return "ok"


if __name__ == '__main__':
    serve(app, host='0.0.0.0', port=os.getenv('PORT', 5000))
