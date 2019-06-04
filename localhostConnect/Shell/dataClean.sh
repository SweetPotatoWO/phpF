#!/bin/bash
#author zll

# crontab命令：
# 监控线上服务稳定性情况
# */1 * * * * sh /data001/data/websites/Connect/Shell/dataClean.sh start >> /data001/data/websites/Connect/Publish/logs/dataClean.log

# PHP命令
php="/usr/local/php7/bin/php  /data001/data/websites/Connect/Publish/cli.php"

# 在这里配置所有需要【守护】的PHP进程
proc_list='Task/synActive/mask/mboxd5b7bb20ad654748c04892be29b67212'

#work 账户运行
#name=$(whoami)
#if [ $name != 'www' ];then
#    echo `date "+%Y/%m/%d %H:%M:%S> "` "必须用www账户"
#    exit
#fi

#开启服务
start() {
    for proc in $proc_list ;do
        arrm=$(ps -ef | grep "`echo $proc`" | grep -v 'grep' | awk -F'cli.php' '{print $2}'| wc -l)
        if [ ${arrm:-0} = 0 ];then
		   $php $proc >/dev/null &
           #$php $(echo $proc | awk -F"\\" '{print $1"\\"$3}') >/dev/null &
           echo  `date "+%Y/%m/%d %H:%M:%S> "` "$proc 进程已经重启"
        else
           echo `date "+%Y/%m/%d %H:%M:%S> "` "$proc 进程已经存在"
        fi
    done
}

#停止服务
stop() {
    for proc in $proc_list ;do
        arrproc=$(ps -ef | grep "`echo $proc`" | awk '{print $2}')
        for p in $arrproc; do
            kill $p;
            echo `date "+%Y/%m/%d %H:%M:%S> "` $p " 进程已杀死！"
        done
    done
    echo `date "+%Y/%m/%d %H:%M:%S> "` "服务已停止！"
}

#check脚本是否运行
check() {
    for proc in $proc_list ;do
        arrspar=$(ps -ef | grep "`echo $proc`" | grep -v 'grep' | awk '{print $2}')
        echo `date "+%Y/%m/%d %H:%M:%S> "` "目前运行的服务监控进程($proc)：" ${arrspar:-"无"}
    done
}

usage() {
    cat <<EOF
        守护进程使用方法（需要 www 用户执行）:

        usage: sh $0 check|start|stop|restart
        start       启动服务
        stop        停止服务
        check       检查服务是否正常
EOF
        exit
}

while true;do
    case $1 in
        start)
            start
            break
            ;;
        help)
            usage
            break
            ;;
        stop)
            stop
            break
            ;;
        check)
            check
            break
            ;;
        *)
            usage
            break
            ;;
    esac
    shift
done

