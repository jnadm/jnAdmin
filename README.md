### 项目说明
* 基于PHP8.1与vue3的前后端分离系统；
* 功能模块：用户管理、角色管理、菜单管理；
* 后台使用最新的laravel10框架，要求至少使用 PHP 版本 8.1;
* 前台使用的技术栈：vue3、TypeScript、scss、vite4.5.0、element-   plus2.4.2、pinia2.1.7;

### 项目部署
#### 后端
* 注意php版本在8.1及以上， windows下部署可以使用phpstudy，linux下可以直接使用docker；
* 安装依赖包：laravel10目录下执行：composer install;
* 配置mysql：修改.env文件的数据库连接相关配置（DB_CONNECTION=mysql）；
* 导入数据：直接将根目录下jnadmin.sql文件导入或执行一下artisan命令
  （1）迁移： php artisan migrate
  （2）填充：php artisan db:seed

### 前端
* 安装依赖包：vue下执行：pnpm install;
* 打开项目： pnpm run dev；

### 本地访问


### 目录结构


### 项目截图


