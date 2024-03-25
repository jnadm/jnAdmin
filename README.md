一、基于PHP8.1与vue3的前后端分离系统；<br/>
功能模块：用户管理、角色管理、菜单管理；
后台使用最新的laravel10框架，要求至少使用 PHP 版本 8.1;
前台使用的技术栈：vue3、TypeScript、scss、vite4.5.0、element-plus2.4.2、pinia2.1.7;
二、后端：<br/>
1、环境部署：
   php最少8.1及以上版本，可以使用phpstudy部署环境；
2、数据库安装：
   环境部署后，新建mysql库，比如jnadmin，导入根目录下sql文件，修改laravel目录下.env数据库相关的配置文件;
   或者使用laravel数据迁移进行操作：
   执行迁移：php artisan migrate;
   执行数据填充；php artisan db:seed;
   
三、前端：(vue3目录下):<br/>
1、安装组件：pnpm -i;
2、运行：pnpm run dev;
