import { createRouter,createWebHashHistory } from "vue-router"
import { constantRoute } from "./routes"

const router = createRouter({
    //路由模式hash
    history: createWebHashHistory(),
    routes: constantRoute,
    //滚动条
    scrollBehavior() {
        return {
            left:0,
            top:0
        }
    }
  })

  export default router