#!/usr/bin/env python

from flask import Flask, abort
from flask.ext.restful import Api, Resource, reqparse
from flask.ext.cors import CORS
from yubico_client import Yubico

app = Flask(__name__, static_url_path="")
CORS(app)

api = Api(app)

app.debug = True


class YubikeyApi(Resource):
    def __init__(self):
        self.reqparse = reqparse.RequestParser()
        self.reqparse.add_argument('password', type=str, required=False, help='No password provided')
        self.reqparse.add_argument('yubikey', type=str, required=True, help='No yubikeu provided')
        self.reqparse.add_argument('user', type=str, required=False, help='No user provided')
        super(YubikeyApi, self).__init__()

    def get(self):
        args = self.reqparse.parse_args()
        return {'content': {'yubikey': args['yubikey']}}, 200

    def post(self):
        args = self.reqparse.parse_args()
        client = Yubico('1851', 'oBVbNt7IZehZGR99rvq8d6RZ1DM=')
        response = {'response': 'success'}
        #import ipdb; ipdb.set_trace()  # BREAKPOINT
        try:
            client.verify(args['yubikey'])
        except:
            response = {'response': 'fail'}
            return {'content': response}, 401

        return {'content': response}, 200

api.add_resource(YubikeyApi, '/validate', endpoint='validate')

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=8000)
