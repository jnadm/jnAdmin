<script setup lang='ts'>
    //引入图标库
    import {User, Lock} from '@element-plus/icons-vue';
    import{reactive, ref} from 'vue'
    import {useRouter} from 'vue-router'
    import { ElNotification } from 'element-plus';
    import { Md5 } from "ts-md5";
    //引入用户相关小仓库
    import useUserStore from '@/store/modules/user';
    let useStore = useUserStore();

    //获取路由器
    let $router = useRouter();
    
    //定义变量控制加载
    let loading = ref(false)

    let loginData = reactive({
        username: '',
        password: '',
    });
    //定义表单提交
    let loginForm = ref();
    //登录
    const login = async() => {
        await loginForm.value.validate();

        //登录按钮点击后开始加载效果
        loading.value = true;

        try {
            //登录
            loginData.password = Md5.hashStr(loginData.password)
            await useStore.userLogin(loginData);
            //编程式导航跳转到展示数据首页
            $router.push('/');
            //登录成功提示信息
            ElNotification({
                type: 'success',
                message: '登录成功',
                duration:1000,
            })
            //登录成功，加载效果消失
            loading.value = false;
        } catch (error) {
            //登录失败，加载效果消失
            loading.value = false;
            //登录失败提示
            ElNotification({
                type: 'error',
                message: (error as Error).message
            })
        }
    }

    /**
     * 自定义校验规则函数
     * @param rules 校验的数据
     * @param value 效验表单的值
     * @param callback 不符合条件返回callback方便，注入错误提示
     */
    const validatorUsername = (rules: any, value: any, callback: any) => {
        if (/^\d{3,16}$/.test(value)) {
            callback();  //符合规则
        } else {
            callback(new Error('用户名长度为5~16位之间'))
        }
    }

    //定义表单验证规则
    const rules = {
        username:[
            {required: true, message: '用户名不能为空', trigger:"blur"},
            {min: 5, max:16, message: '用户名长度为5~16位之间', trigger:"change"},
            // {trigger:'change', validator:validatorUsername}
        ],
        password:[
            {required: true, min:6,max:32, message: '密码必须是6~20位之间'}
        ],
    }

    

   
</script>

<template>
    <div class="container">
        <div class="login-container">
            <h2>Login</h2>
            <el-form class="login_form" :model="loginData" :rules="rules" ref="loginForm">
                <el-form-item prop="username">
                        <el-input placeholder="账号：" v-model="loginData.username" :prefix-icon="User"></el-input>
                </el-form-item>
                <el-form-item prop="password">
                        <el-input placeholder="密码：" v-model="loginData.password" :prefix-icon="Lock" type="password" show-password></el-input>
                </el-form-item>
                <el-form-item>
                        <el-button :loading="loading" class="login_button" type="primary" size="default" @click="login">登录</el-button>
                </el-form-item>
            </el-form>
        </div>
    </div>
    
</template>

<style lang='scss' scoped>
 
 .container {
    font-family: Arial, sans-serif;
	background-color:#1677b3;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }
 
  .login-container {
    background-color: #fff;
    border-radius: 8px;
    padding: 50px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 350px;
    text-align: center;
  }

  .login-container h2 {
    font-size: 25px;
    font-weight: bold;
    margin-bottom: 20px;

  }

  .login_button {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
  }

  .login_button {
    background-color: #495c69;
    color: white;
    border: none;
    cursor: pointer;
  }

  .login-container input[type="submit"]:hover {
    background-color: #495c69;
  }
</style>