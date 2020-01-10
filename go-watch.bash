#!/bin/bash
# base on https://github.com/buexplain/go-watch
go-watch run \
--preCmd "php ~/hyperf-skeleton/bin/hyperf.php di:init-proxy" \
--preCmdIgnoreError=true \
--cmd "php" \
--args "~/hyperf-skeleton/bin/hyperf.php, start" \
--files "~/hyperf-skeleton/test/.env" \
--folder "~/hyperf-skeleton/app/, ~/hyperf-skeleton/config/" \
--autoRestart=true
