import { createApp } from 'vue'
import App from './App.vue'

//安装elementPlus
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'

//安装elementPlus中文语言包
import zhCn from 'element-plus/dist/locale/zh-cn.mjs'


//引入全局样式
import "@/styles/index.scss"

//安装路由
import router from '@/routes/index'

//引入仓库
import pinia from './store'

const app = createApp(App)
//挂载elementPlus
app.use(ElementPlus, {
    locale: zhCn,
  })

  //引入svg图标库
import 'virtual:svg-icons-register'

//挂载路由
app.use(router)

//安装仓库
app.use(pinia)

//引入自定义插件：注册整个项目全局组件
import globalComponent from '@/components'
//安装自定义插件
app.use(globalComponent)
//引入全局路由守卫
import './premission'

app.mount('#app')
