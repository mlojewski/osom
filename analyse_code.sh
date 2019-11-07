#!/usr/bin/env bash

LIGHT_GREEN_BACKGROUND='\e[102m'
MAGENTA_TEXT='\e[95m'
DEFAULT='\e[49m'
DEFAULT_TEXT='\e[39m'

echo -e "${LIGHT_GREEN_BACKGROUND}${MAGENTA_TEXT}Running parallel lint ${DEFAULT}${DEFAULT_TEXT} \xE2\x9C\xA8"
./vendor/bin/parallel-lint --exclude vendor .

echo -e "${LIGHT_GREEN_BACKGROUND}${MAGENTA_TEXT}Running php code sniffer ${DEFAULT}${DEFAULT_TEXT} \xE2\x9C\xA8"
./vendor/bin/phpcs --standard=./phpcs.xml.dist

echo -e "${LIGHT_GREEN_BACKGROUND}${MAGENTA_TEXT}Running php stan ${DEFAULT}${DEFAULT_TEXT} \xE2\x9C\xA8"
./vendor/bin/phpstan analyse src/

echo -e "${LIGHT_GREEN_BACKGROUND}${MAGENTA_TEXT}Running phpcpd ${DEFAULT}${DEFAULT_TEXT} \xE2\x9C\xA8"
./vendor/bin/phpcpd src/

echo -e "${LIGHT_GREEN_BACKGROUND}${MAGENTA_TEXT}Running php md${DEFAULT}${DEFAULT_TEXT} \xE2\x9C\xA8"
./vendor/bin/phpmd src,features text ruleset.xml
