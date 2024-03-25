<script setup lang='ts'>
import {watch,ref,nextTick} from 'vue';
import useLayOutSettingStore from '@/store/modules/setting';
let layoutSettingStore = useLayOutSettingStore();

//控制当前组件是否销毁重建
let flag = ref(true);

watch(() => layoutSettingStore.refresh, ()=>{
    //点击刷新，销毁组件
    flag.value = false;
    nextTick(() =>{
        flag.value = true;
    })
})
</script>

<template>
    <div>
        <!--路由过度效果-->
        <router-view v-slot="{ Component }">
        <!-- <transition name="fade"> -->
            <component :is="Component" v-if="flag"/>
        <!-- </transition> -->
        </router-view>
    </div>
</template>

<style lang='scss' scoped>
// .fade-enter-from{
//     opacity:0;
//     transform:scale(0);
// }
// .fade-enter-active{
//     transition:all 1s;
// }
// .fade-enter-to{
//     transform:totate(360 deg); 
//     transform:scale(1);
// }

</style>