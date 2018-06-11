#!/bin/bash

echo "setting up iptables rules for wifi portal....."
#####   setup rules for login-portal  #######
iptables -t mangle -N captiveportal
iptables -t mangle -A PREROUTING -i wlan0 -p udp --dport 53 -j RETURN
iptables -t mangle -A PREROUTING -i wlan0 -j captiveportal
iptables -t mangle -A captiveportal -j MARK --set-mark 1
iptables -t nat -A PREROUTING -i wlan0  -p tcp -m mark --mark 1 -j DNAT --to-destination 10.0.0.1
sysctl -w net.ipv4.ip_forward=1
iptables -A FORWARD -i wlan0 -j ACCEPT
iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE
echo "done"
