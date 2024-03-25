
//引入项目中的全部全局组件
import SvgIcon from './SvgIcon/index.vue'

//全局对象
const allGloablComponent :any = {SvgIcon}

//引入element-plus全部图标组件
import * as ElementPlusIconsVue from '@element-plus/icons-vue'

//对外暴露插件对象
export default {
    //方法名必须叫install
    install(app :any) {
        //注册项目中全部的全局组件
        Object.keys(allGloablComponent).forEach(key => {
            //注册为全局组件
            app.component(key, allGloablComponent[key])
            //将element-plus提供的图标注册为全局组件
            for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
                app.component(key, component)
              }
        })
    }
}