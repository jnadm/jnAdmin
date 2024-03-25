//小仓库：layout组件相关配置仓库
import {defineStore} from 'pinia'

let userlayOutSettingStore = defineStore('SettingStore', {
    state: () => {
        return {
            fold:false, //用于顶部控制菜单是折叠还是收起
            refresh:false,
        }
    }
});

export default userlayOutSettingStore;