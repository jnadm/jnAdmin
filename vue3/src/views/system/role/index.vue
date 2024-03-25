<script setup lang='ts'>
import { reactive, ref, onMounted, watch} from 'vue'
import { UploadProps} from 'element-plus'
import { Plus } from '@element-plus/icons-vue'
import { ElTree } from 'element-plus'
import message from "@/utils/message";
//引入接口
import {apiRoleLists, 
        apiRoleAdd, 
        apiRoleEdit, 
        apiRoleDetails,
        apiRoleBatchDel,
        setRolePermission,
    } from "@/api/role"

import {apiMenuLists} from "@/api/menu"
    //定义加载变量
    const loading = ref(true)
    //定义列表数据
    let tableData = reactive({
        column:[],  //表格列
        lists:[],   //数据
        total:0,    //总数
        currPage:1, //当前页
        pageSize:10, //每页显示条数
    })
    //添加弹窗是否显示
    const dialogFormVisible = ref(false)
    //定义表单数据
    const initForm = () => {
        return {
            id:0,
            name:'',
            status:1,
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


    //挂载
    onMounted(()=>{
        initLists();
    })

    //监听页码与每页条数发生变化
    watch([()=>tableData.currPage, ()=>tableData.pageSize], () => {
        initLists(); 
    })

    //初始化请求函数
    interface obj {[key: string]: any}
    const initLists = async () => {
        let params:obj = {
                    'page':tableData.currPage,
                    'page_size':tableData.pageSize
            };
        if (search.name) {
            params.name = search.name
        }
        try {
            await apiRoleLists(params).then(function(response: any){
                    tableData.column = response.data.column
                    tableData.lists = response.data.lists.data 
                    tableData.total = response.data.lists.total
            })
            loading.value = false
        } catch (error) {
            console.log('请求数据失败');
        }
    }

    //多选函数
    let ids: number[] = [];
    const handleSelectionChange = (data: any) => {
        ids = [];
        data.forEach((item:any) => {
            ids.push(item.id)
        });
    }

    //删除单个
    const del = async (id: number) => {
        await apiRoleBatchDel({"ids":[id]}).then(function(){
                message.success();
            })
        initLists()
    }
    //批量删除
    const batchDel = async () => {
            await apiRoleBatchDel({"ids":ids}).then(function(){
                message.success(); 
            })
        initLists()
    }

    let dialogTitle = ref('')
    //添加按钮
    const add = () => {
        resetForm()  //初始化表单值
        dialogTitle.value = '添加角色' //弹窗标题
        dialogFormVisible.value = true
    }
   //编辑数据
    const edit = async (id: number) => {
        resetForm()  //初始化表单值
        dialogTitle.value = '编辑角色'//弹窗标题
        await getDetails(id); 
        dialogFormVisible.value = true
    }

   
    const submit = () => {
         if (form.id) {
            //修改
            apiRoleEdit(form).then(function(response: any){
                if (response.code == 200) {
                    dialogFormVisible.value = false
                    initLists();
                    message.success();
                }
            })
        } else {
            //添加
            apiRoleAdd(form).then(function(response: any){
                if (response.code == 200) {
                    dialogFormVisible.value = false
                    initLists();
                    message.success();
                }
            })
        }
    }

    const getDetails = async (id:number) => {
        await apiRoleDetails({'id':id}).then(function(response:any){
            if (response.code == 200) {
                form.id = response.data.id
                form.name = response.data.name
                form.status = response.data.status
                form.sort = response.data.sort
              
            } 
        }) 
    }

    //权限设置
    const dialogSetPermisson = ref(false)
     //定义列表数据
     let menuTableData = reactive({
        column:[],  //表格列
        lists:[],   //数据
    })
     //定义树
     const defaultProps = {
        children: 'children',
        label: 'name',
        disabled: 'disabled',
    }

    
    const checkTreeCodes = ref([]) //定义数选中的元素
    const roleId = ref(0)   //定义权限配置的当前校色

    const setPermission = async (id:any, menuCodes: any) => {
        console.log(menuCodes)
        roleId.value = id
        checkTreeCodes.value = menuCodes
        await apiMenuLists({}).then(function(response:any){
            menuTableData.column = response.data.column
            menuTableData.lists = response.data.data 
            dialogSetPermisson.value = true
        })
    }
  
    //提交权限配置
    const treeRef = ref<InstanceType<typeof ElTree>>()
    const submitPermission = async() => {
        let menuIds = new Array()
        let checkLists = treeRef.value!.getCheckedNodes(false, false)
        checkLists.forEach(function(item, index){
            menuIds[index] = item.code
        })
        console.log(menuIds)
        await setRolePermission({
                'id':roleId.value, 
                'menu_codes':menuIds
            }).then(function(response:any){
                if (response.code == 200) {
                    initLists();
                    dialogSetPermisson.value = false
                    message.success();
                }
        })
         
    }

    
</script>

<template>
    <div class="container">
        <div class="search">
            <el-input placeholder="角色名称"  v-model="search.name" style="width: 150px;"/>
            <el-button type="primary" plain @click="initLists">查询</el-button>
        </div>
        <div class="content">
            <!--按钮区域-->
            <div class="conent-button">
                <el-button type="primary" @click="add">添加</el-button>
                <el-popconfirm @confirm="batchDel" title="确认操作?">
                    <template #reference> <el-button type="danger">批量删除</el-button></template>
                </el-popconfirm>
            </div>
            <!--数据表区域-->
            <div class="content-table">
                <el-table 
                v-loading="loading"
                :data="tableData.lists" 
                style="width: 100%" 
                border
                @selection-change="handleSelectionChange"
                >
                    <el-table-column type="selection" width="39" />
                    <el-table-column v-for="(item,index) in tableData.column" 
                    :prop="item['colKey']" :key="index" :label="item['value']" align="center"
                    >
                    <template v-if=" item['colKey'] == 'status' "  v-slot="scope">
                        <span v-if="scope.row['status']"><el-tag type="success">启用</el-tag></span>
                        <span v-else><el-tag type="danger">停用</el-tag></span>
                    </template>
                    
                    </el-table-column>

                    <el-table-column label="操作" align="center" width="300px">
                        <template v-slot="scope">
                            <el-button size="small" @click="setPermission(scope.row['id'], scope.row['menuCodes'])">权限设置</el-button>
                            <el-button size="small" @click="edit(scope.row['id'])">编辑</el-button>
                            <el-popconfirm @confirm="del(scope.row['id'])" title="确认操作?">
                                <template #reference> 
                                    <el-button size="small" type="danger">删除</el-button>
                                </template>
                            </el-popconfirm>
                        </template>

                    </el-table-column>

                </el-table>
            </div>
            <!--分页区域-->
            <div class="content-page">
                <el-pagination
                    v-model:current-page="tableData.currPage" 
                    v-model:page-size="tableData.pageSize"
                    layout="total, sizes, prev, pager, next, jumper"
                    :total="tableData.total"
                />
            </div>
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
            <el-form-item label="角色名称：" :rules="[{ required: true, message: '角色名称不能为空' }]">
                <el-input v-model="form.name" />
            </el-form-item>
            <el-form-item label="状态：">
                <el-radio-group v-model="form.status" class="ml-4">
                    <el-radio :label="1" size="large">启用</el-radio>
                    <el-radio :label="0" size="large">停用</el-radio>
                </el-radio-group>
            </el-form-item>
            <el-form-item label="序号：" :rules="[{ required: true, message: '序号不能为空' }]">
                <el-input v-model="form.sort" />
            </el-form-item>
        </el-form>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogFormVisible = false">取消</el-button>
                <el-button type="primary" @click="submit">保存</el-button>
            </span>
        </template>
    </el-dialog>

    <!--权限设置-->
    <el-dialog v-model="dialogSetPermisson" title="权限设置" class="dialog-title" style="font-size:10px">
        <el-tree 
                :data="menuTableData.lists" 
                :props="defaultProps" 
                show-checkbox 
                default-expand-all
                node-key="code"
                ref="treeRef"
                :default-checked-keys="checkTreeCodes"
            >
            <template #default="{ _, data }">
                <span class="custom-tree-node">
                    <span>{{ data.name }}</span>
                </span>
            </template>

            </el-tree>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogSetPermisson = false">取消</el-button>
                <el-button type="primary" @click="submitPermission">保存</el-button>
            </span>
        </template>
    </el-dialog>


</template>

<style lang='scss' scoped>
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
        transition: var(--el-transition-duration-fast);
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