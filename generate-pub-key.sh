#!/bin/bash

openssl rsa -in teste-app-rsa.pem -pubout > teste-app-rsa.pub
openssl rsa -in teste-user-rsa.pem -pubout > teste-user-rsa.pub
