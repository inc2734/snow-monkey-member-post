#!/usr/bin/env bash

set -e;

TMPDIR=${TMPDIR-/tmp}
TMPDIR=$(echo $TMPDIR | sed -e "s/\/$//")
WP_TESTS_DIR=${WP_TESTS_DIR-$TMPDIR/wordpress-tests-lib}
WP_CORE_DIR=${WP_CORE_DIR-$TMPDIR/wordpress/}

dir=$(pwd)

cd ${dir}

if [ -e ${dir}/bin/install-wp-tests.sh ]; then
  echo 'DROP DATABASE IF EXISTS wordpress_test;' | mysql -u root

  if [ -e ${WP_CORE_DIR} ]; then
    rm -fr ${WP_CORE_DIR}
  fi

  if [ -e ${WP_TESTS_DIR} ]; then
    rm -fr ${WP_TESTS_DIR}
  fi

  bash "${dir}/bin/install-wp-tests.sh" wordpress_test root '' localhost latest;
  vendor/bin/phpunit --configuration= ${dir}/phpunit.xml.dist
else
  echo "${dir}/bin/install-wp-tests.sh not found."
fi;
