<script setup lang='ts'>
import { reactive, ref, onMounted} from 'vue'
import message from "@/utils/message";
//引入接口
import {apiMenuLists, 
        apiMenuAdd, 
        apiMenuEdit, 
        apiMenuDetails,
        apiMenuBatchDel,
    } from "@/api/menu"
    //定义加载变量
    const loading = ref(true)
    //定义列表数据
    let tableData = reactive({
        column:[],  //表格列
        lists:[],   //数据
     
    })

    //定义树
    const defaultProps = {
        children: 'children',
        label: 'name',
        disabled: 'disabled',
    }
  
    //添加弹窗是否显示
    const dialogFormVisible = ref(false)
    //定义表单数据
    const initForm = () => {
        return {
            id:0,
            name:'',
            parent_code:'',
            path:'',
            component:'',
            type: 1,
            is_show:1,
            sort:0,
        }
    }
    //定义搜索项
    let search = reactive({
        name: '',  
    }) 
    //表单数据绑定
    const form = reactive(initForm())
    //初始化表单值
    const resetForm = () => {
        Object.assign(form, initForm());
    }

    //自定义下拉树名称
    let menuProps = {
        value:'code',
        label:'name',
    }


    //挂载
    onMounted(()=>{
        initLists();
    })

    //初始化请求函数
    interface obj {[key: string]: any}
    const initLists = async () => {
        let params:obj = {};
        try {
            await apiMenuLists(params).then(function(response: any){
                    tableData.column = response.data.column
                    tableData.lists = response.data.data 
            })
            loading.value = false
        } catch (error) {
            console.log('请求数据失败');
        }
    }

    //删除单个
    const del = async (id: number) => {
        await apiMenuBatchDel({"ids":[id]}).then(function(){
                message.success();
            })
        initLists()
    }
    
    let dialogTitle = ref('')
    //添加按钮
    const add = () => {
        resetForm()  //初始化表单值
        dialogTitle.value = '添加菜单' //弹窗标题
        dialogFormVisible.value = true
    }
    //列表页添加按钮(code在添加菜单时，默认列表页数值使用)
    const addMenu = (code :string) => {
        resetForm()  //初始化表单值
        form.parent_code = code
        dialogTitle.value = '添加菜单' //弹窗标题
        dialogFormVisible.value = true
    }
   //编辑数据
    const edit = async (id: number) => {
        resetForm()  //初始化表单值
        dialogTitle.value = '编辑菜单'//弹窗标题
        await getDetails(id); 
        dialogFormVisible.value = true
    }

   
    const submit = () => {
        if (form.id) {
            //修改
            apiMenuEdit(form).then(function(response: any){
                if (response.code == 200) {
                    dialogFormVisible.value = false
                    initLists(); 
                    message.success();
                }
            })
        } else {
            //添加
            apiMenuAdd(form).then(function(response: any){
                if (response.code == 200) {
                    dialogFormVisible.value = false
                    initLists();
                    message.success();
                }
            })
        }
    }

    const getDetails = async (id:number) => {
        await apiMenuDetails({'id':id}).then(function(response:any){
            if (response.code == 200) {
                form.id = response.data.id
                form.name = response.data.name
                form.parent_code = response.data.parent_code
                form.path = response.data.path 
                form.component = response.data.component
                form.type = response.data.type
                form.is_show = response.data.is_show
                form.sort = response.data.sort
            } 
        }) 
    }

</script>

<template>
    <div class="container">
        
        <div class="content">
            <!--按钮区域-->
            <div class="conent-button">
                <el-button type="primary" @click="add">添加</el-button>
                <!-- <el-popconfirm @confirm="batchDel" title="确认操作?">
                    <template #reference> <el-button type="danger">批量删除</el-button></template>
                </el-popconfirm> -->
            </div>
            <!--数据表区域-->
            <el-tree 
                :data="tableData.lists" 
                :props="defaultProps" 
                show-checkbox 
                default-expand-all
                node-key="id"
            >
            <template #default="{ _, data }">
                <span class="custom-tree-node">
                <span>{{ data.name }}<el-tag class="ml-2" type="danger" v-if="!data.is_show">隐藏</el-tag></span>
                <span @click.stop>
                    <el-button size="small" @click="addMenu(data.code)">添加</el-button>
                    <el-button size="small" @click="edit(data.id)">编辑</el-button>
                    <el-popconfirm @confirm="del(data.id)" title="确认操作?">
                        <template #reference> 
                            <el-button size="small" type="danger">删除</el-button>
                        </template>
                    </el-popconfirm>
                </span>
                </span>
            </template>

            </el-tree>
          
            
        </div>
    </div>

    <!--添加弹窗-->
    <el-dialog v-model="dialogFormVisible" :title="dialogTitle" class="dialog-title" style="font-size:10px">
        <el-form 
        :model="form" 
        label-position="top" 
        label-width="150px"
        style="max-width: 500px"
        class="demo-ruleForm"
         >
            <el-form-item label="菜单名称：" :rules="[{ required: true, message: '菜单名称不能为空' }]">
                <el-input v-model="form.name" />
            </el-form-item>
            
            <el-form-item label="菜单父级：">
 
                <el-tree-select
                    v-model="form.parent_code"
                    :data="tableData.lists"
                    check-strictly
                    filterable
                    :render-after-expand="false"
                    style="width: 240px"
                    :props="menuProps"
                />
            </el-form-item>

            <el-form-item label="后端路由：">
                <el-input v-model="form.path" />
            </el-form-item>
            <el-form-item label="前端路由：">
                <el-input v-model="form.component" />
            </el-form-item>
            <el-form-item label="类型：">
                <el-radio-group v-model="form.type" class="ml-4">
                    <el-radio :label="1" size="large">菜单</el-radio>
                    <el-radio :label="2" size="large">按钮</el-radio>
                </el-radio-group>
            </el-form-item>
            <el-form-item label="是否显示：">
                <el-radio-group v-model="form.is_show" class="ml-4">
                    <el-radio :label="1" size="large">是</el-radio>
                    <el-radio :label="0" size="large">否</el-radio>
                </el-radio-group>
            </el-form-item> 
            <el-form-item label="序号：" >
                <el-input v-model="form.sort"/>
            </el-form-item>
        </el-form>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogFormVisible = false">取消</el-button>
                <el-button type="primary" @click="submit">保存</el-button>
            </span>
        </template>
    </el-dialog>

</template>

<style lang='scss' scoped>
//树结构
.custom-tree-node {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 14px;
  padding-right: 8px;
  line-height: 50px;
}
//加载样式
.example-showcase .el-loading-mask {
  z-index: 9;  
}

.container {
    display: flex;
    margin-top: 10px;
    flex-direction:column;
    height: calc(100vh - 50px - 20px); //减去上面50px和padding为10;
    .search{
        display: flex;
        padding: 10px;
        background-color: #fff;
        align-items:center;
        gap:10px;
        .el-input .button {
            height: 25px;
            vertical-align: middle;
        }
    }
    .content{
        display: flex;
        flex-direction:column;
        margin-top:8px;
        background-color: #fff;
        padding: 20px;
        gap:10px;
        justify-content: flex-start; 
        .content-page .el-pagination{
            justify-content: right;
        }
    }

}

</style>

<style lang='scss'>
/* 解决弹窗居中问题 */
.el-dialog{
    display: flex;
    flex-direction: column;
    margin:0 !important;
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    /*height:600px;*/
    max-height:calc(100% - 30px);
    max-width:calc(100% - 30px);
  }
  .el-dialog .el-dialog__body{
    flex:1;
    overflow: auto;
  }
  /* 解决弹窗居中问题 */

    .el-dialog__header .el-dialog__title{
        font-size: 15px;
        font-weight: bold;
     }

    .avatar-uploader .el-upload {
        border: 1px dashed var(--el-border-color);
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        //transition: var(--el-transition-duration-fast);
        }

        .avatar-uploader .el-upload:hover {
        border-color: var(--el-color-primary);
        }

        .el-icon.avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 100px;
        height: 100px;
        text-align: center;
        }
</style>