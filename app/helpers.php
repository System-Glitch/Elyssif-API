<?php
/*
 * Elyssif-API
 * Copyright (C) 2019 Jérémy LAMBERT (System-Glitch)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

function bitcoind()
{
    return new Denpa\Bitcoin\Client('http://'.env('RPC_USER').':'.env('RPC_PASSWORD').'@'.env('BITCOIND_HOST'));
}

function minPrice()
{
    return floatval(env('ELYSSIF_FEES', 0.0004)) + floatval(env('MIN_SELLER_PROFIT', 0.0004)) + floatval(env('BITCOIN_FEES', 0.0000332));
}

function checkBitcoinAddress($address)
{
	try {
        return bitcoind()->getAddressInfo($address)->result() != null;
    } catch(Denpa\Bitcoin\Exceptions\BadRemoteCallException $e) {
        return false;
    }
}