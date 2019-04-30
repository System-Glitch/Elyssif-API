#!/usr/bin/python3
# Copyright (c) 2015-2018 The Bitcoin Core developers
# Distributed under the MIT software license, see the accompanying
# file COPYING or http://www.opensource.org/licenses/mit-license.php.
# forked by System-Glitch

from argparse import ArgumentParser
from base64 import urlsafe_b64encode
from binascii import hexlify
from getpass import getpass
from os import urandom

import os
import hmac

def generate_salt(size):
    """Create size byte hex salt"""
    return hexlify(urandom(size)).decode()

def generate_password():
    """Create 32 byte b64 password"""
    return urlsafe_b64encode(urandom(32)).decode('utf-8')

def password_to_hmac(salt, password):
    m = hmac.new(bytearray(salt, 'utf-8'), bytearray(password, 'utf-8'), 'SHA256')
    return m.hexdigest()

def main():
    parser = ArgumentParser(description='Create login credentials for a JSON-RPC user')
    parser.add_argument('username', help='the username for authentication')
    parser.add_argument('bitcoinconf', help='the path of the bitcoin configuration file')
    parser.add_argument('env', help='the path of the env file')
    parser.add_argument('password', help='leave empty to generate a random password or specify "-" to prompt for password', nargs='?')
    args = parser.parse_args()

    if not args.password:
        args.password = generate_password()
    elif args.password == '-':
        args.password = getpass()

    # Create 16 byte hex salt
    salt = generate_salt(16)
    password_hmac = password_to_hmac(salt, args.password)

    f = open(args.bitcoinconf,"a")
    f.write('\nrpcuser={0}\n'.format(args.username))
    f.write('rpcauth={0}:{1}${2}\n'.format(args.username, salt, password_hmac))
    f.write('rpcpassword={0}\n'.format(args.password))
    f.close()

    f = open(args.env,"a")
    f.write('\nRPC_USER={0}\n'.format(args.username))
    f.write('RPC_PASSWORD={0}\n'.format(args.password))
    f.close()

    print('rpcuser={0}'.format(args.username))
    print('rpcauth={0}:{1}${2}'.format(args.username, salt, password_hmac))
    print('rpcpassword={0}'.format(args.password))

if __name__ == '__main__':
    main()
