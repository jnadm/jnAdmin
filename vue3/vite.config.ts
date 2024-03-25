import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
//引入svg图标库
import { createSvgIconsPlugin } from 'vite-plugin-svg-icons'
import path from 'path'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue(),
    createSvgIconsPlugin({
      // 指定需要缓存的图标文件夹
      iconDirs: [path.resolve(process.cwd(), 'src/assets/icons')],
      // 指定symbolId格式
      symbolId: 'icon-[dir]-[name]',
    })
  ],
  resolve: {
    //设置别名
    alias: {
      '@': path.resolve(__dirname, 'src'),
    }
  },
  //scss全局变量配置
  css:{
    preprocessorOptions:{
      scss:{
        javascriptEnabled: true,
        additionalData:'@import "./src/styles/variable.scss";' //引入scss文件
      }
    }
  },
  //跨域配置
  server: {
    proxy: {
      "/api": {
        target: "http://test10.com",
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, ""),
      },
    },
  },
})
