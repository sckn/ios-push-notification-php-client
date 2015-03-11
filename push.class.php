<?php
/*
 *  Apple IOS Send Notify PHP Client
 *  Seçkin ALAN - seckinalan@gmail.com
 * 
 *  This program is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation; either version 2
 *  of the License, or (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.
 * 
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *  MA 02110-1301, USA.
 * 
 * */
class push{
    public $pem_path;
    public $device_token;
    public $alert;
    public $sound = 'default';

    public function __construct(){

        $this->pem_path = getcwd().'/cert.pem';

    }

    public function send(){

        $context = stream_context_create();
        stream_context_set_option($context, 'ssl', 'local_cert', $this->pem_path);

        $server = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195',
            $err,$errstr, 60,
            STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $context
        );

        if(!$server){
            throw new Exception("Failed to connect:\n$err\n\n$errstr" . PHP_EOL);
        }

        $data["aps"] = array(
            'alert' => $this->alert,
            'sound' => $this->sound
        );

        $data = json_encode($data);

        $binary_data = chr(0) . pack('n', 32) . pack('H*', $this->device_token);
        $binary_data .= pack('n', strlen($data)) . $data;

        $result = !fwrite($server, $binary_data, strlen($binary_data))?false:true;

        fclose($server);

        return $result;

    }
}
