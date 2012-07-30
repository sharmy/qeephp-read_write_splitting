# <?php die(); ?>

## 注意：书写时，缩进不能使用 Tab，必须使用空格

#############################
# 数据库设置
#############################
devel:
  read:
    -
      driver:     mysql
      host:       127.0.0.1
      login:      test
      password:   123456
      database:   mydatabase_v1
      charset:    utf8
      prefix:  
      conn:       read
    -
      driver:     mysql
      host:       127.0.0.1
      login:      test
      password:   123456
      database:   mydatabase_v1
      charset:    utf8
      prefix:
      conn:       read
  write:
      driver:     mysql
      host:       127.0.0.1
      login:      test
      password:   123456
      database:   mydatabase_v1
      charset:    utf8
      prefix:
      conn:       write

prodect:
  _use: devel

# test 模式
test:
  driver:     mysql
  host:       127.0.0.1
  login:      test
  password:   123456
  database:   mydatabase_v1
  charset:    utf8
  prefix:

# deploy 模式
deploy:
  _use: devel

another_mysql_dsl:
  read:
    -
      driver:     mysql
      host:       127.0.0.1
      login:      test
      password:   123456
      database:   mydatabase_v2
      charset:    utf8
      prefix:     dokomo_wap_
      conn:       read
    -
      driver:     mysql
      host:       127.0.0.1
      login:      test
      password:   123456
      database:   mydatabase_v2
      charset:    utf8
      prefix:     dokomo_wap_
      conn:       read
  write:
    driver:     mysql
    host:       127.0.0.1
    login:      test
    password:   123456
    database:   mydatabase_v2
    charset:    utf8
    prefix:     dokomo_wap_
    conn:       write