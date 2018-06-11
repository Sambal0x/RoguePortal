#!/bin/bash

echo "clearing ip tables......."
iptables -F -t nat
iptables -F -t mangle
iptables -F
iptables -X
iptables -X -t nat
iptables -X -t mangle
echo "done"
