DROP DATABASE `spider`;
CREATE DATABASE `spider`
  CHARACTER SET utf8
  COLLATE utf8_general_ci;
USE `spider`;
CREATE TABLE `users` (
  `user_id`   INT                                                                                                                                                          AUTO_INCREMENT
  COMMENT '用户唯一标识',
  `nickname` VARCHAR(60) COMMENT '用户昵称,唯一',
  `rank`     INT      NOT NULL                                                                                                                                                 DEFAULT 0
  COMMENT '声望',
  `sex`      TINYINT NOT NULL                                                                                                                                             DEFAULT 0
  COMMENT '性别:0 未知,1 男,2 女',
  `city`     VARCHAR(60) COMMENT '城市',
  `school`   VARCHAR(60) COMMENT '毕业学校',
  `carrer`   VARCHAR(60) COMMENT '职业',
  `company`  VARCHAR(60) COMMENT '公司',
  `website`  VARCHAR(60) COMMENT '个人网站',
  `summary`  VARCHAR(250) COMMENT '个人简介',
  `followed` INT     NOT NULL                                                                                                                                             DEFAULT 0
  COMMENT '粉丝',
  `following` INT    NOT NULL                                                                                                                                              DEFAULT 0
  COMMENT '关注',
  `checked`   TINYINT NOT NULL                                                                                                                                             DEFAULT 0
  COMMENT '是否爬过',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY (`nickname`)
)CHARACTER SET utf8
  DEFAULT CHARSET = utf8