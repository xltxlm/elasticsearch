#!/bin/sh
# .bashrc
#$Id: .bashrc 519 2014-05-27 11:29:07Z sh_xlt $
# Source global definitions
#if [ -f /etc/bashrc ]; then
#        . /etc/bashrc
#fi
uniqmd5=`cat /proc/sys/kernel/random/uuid`

ip=`ip addr | grep -v "127.0.0.1" | grep "inet " | awk -F" " '{print $2}' | awk -F"/" '{print $1}' | head -n1`

#grep命令高亮命中的词
export GREP_OPTIONS='--color=auto'
export GREP_COLOR='1;31'
# User specific aliases and functions

export LANG=zh_CN.utf-8
export PS1="\[\e[36;1m\]\A \u@\[\e[32;1m\]$ip \w> \[\e[0m\]"

#PHP的路径设置
export PATH=~/bin:/usr/local/mysql/bin:/usr/local/php7/bin/:/usr/local/hadoop/bin/:$PATH;
export PATH=~/shell/:$PATH;


#文件创建的权限.同组也可以进行修改
umask 002


#查看端口命令
alias dk='netstat -tnlp'
alias chmodx='find ./ -name "*.sh" | xargs chmod +x'
alias ll='ls -l --color=auto'

#比较2个文件夹的不同
alias ddd='~/shell/ddd.sh'
alias tcp='netstat -an | grep ESTABLISHED | grep tcp'

alias 00='cd ~'
alias 000='up'
alias 0000='~/shell/install.sh codeRollback'
alias nn='cd /usr/local/nginx/logs/'

function e()
{
    cd  /opt/phplog/
    tail -f  `ls *$1* | grep -v ".log$" `  php-error.log  -n0
}
