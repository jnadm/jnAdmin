<script setup lang='ts'>
import { reactive, ref, onMounted, watch} from 'vue'
import { UploadProps} from 'element-plus'
import { Plus } from '@element-plus/icons-vue'
import message from "@/utils/message";
//引入接口
import {apiAccountLists, 
        apiAccountAdd, 
        apiAccountEdit, 
        apiAccountDetails,
        apiAccountBatchDel,
    } from "@/api/admin"
//引入接口
import {
    apiRoleLists, 
} from "@/api/role"

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
            username:'',
            real_name:'',
            nickname:'',
            mobile:'',
            email:'',
            avatar:0,
            avatar_url:'',
            role_id:'',
            role_name:'',
            is_update_pass:0,
            password:'',
            status:1,
            created_id:'',
            updated_id:'',
            created_at:'',
            updated_at:'',
        }
    }
    //定义搜索项
    //定义列表数据
    let search = reactive({
        username: '',  
        mobile: '', 
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
        getRoleList();
    })

    //监听页码与每页条数发生变化
    watch([()=>tableData.currPage, ()=>tableData.pageSize], () => {
        initLists(); 
    })
    watch(dialogFormVisible, () => {
        form.is_update_pass = 0
    })

    //初始化请求函数
    interface obj {[key: string]: any}
    const initLists = async () => {
        loading.value = true
        let params:obj = {
                    'page':tableData.currPage,
                    'page_size':tableData.pageSize
            };
        if (search.username) {
            params.username = search.username
        }
        if (search.mobile) {
            params.mobile = search.mobile
        }
        try {
            await apiAccountLists(params).then(function(response: any){
                    tableData.column = response.data.column
                    tableData.lists = response.data.lists.data 
                    tableData.total = response.data.lists.total
            })
            loading.value = false
        } catch (error) {
            console.log('请求数据失败');
        }
    }

    let roleList: any[] = [];
    const getRoleList = async() => {
        let params:obj = {
                    'page':1,
                    'page_size':1000,
            };
        await apiRoleLists(params).then(function(response: any){
                roleList = response.data.lists.data 
              })
    }

    //多选函数
    let ids: number[] = [];
    const handleSelectionChange = (data: any) => {
        ids = [];
        data.forEach((item:any) => {
            ids.push(item.id)
        });
        console.log(ids)
    }
    //删除单个
    const del = async (id: number) => {
            await apiAccountBatchDel({"ids":[id]}).then(function(){
                    message.success();
                })
            initLists()
    }
    //批量删除
    const batchDel = async () => {
            await apiAccountBatchDel({"ids":ids}).then(function(){
                message.success(); 
            })
            initLists()
    }

    let dialogTitle = ref('')
    let ussernameDisabled = ref(false)  //编辑时禁止编辑用户名
    
    //添加或编辑数据
    const save = async (id: number = 0) => {
        resetForm()  //初始化表单值
        dialogFormVisible.value = true
        if (id == 0) {
            ussernameDisabled.value = false
            dialogTitle.value = '添加用户' //弹窗标题
        } else {
            dialogTitle.value = '编辑用户'//弹窗标题
            ussernameDisabled.value = true
            await getDetails(id); 
        }

    }

    const submit = () => {
         if (form.id) {
            //修改
            apiAccountEdit(form).then(function(response: any){
                if (response.code == 200) {
                    dialogFormVisible.value = false
                    message.success();
                }
            })
        } else {
            //添加
            apiAccountAdd(form).then(function(response: any){
                if (response.code == 200) {
                    dialogFormVisible.value = false
                    initLists();
                    message.success();
                }
            })
        }
        form.is_update_pass = 0
    }

    const getDetails = async (id:number) => {
        await apiAccountDetails({'id':id}).then(function(response:any){
            if (response.code == 200) {
                form.id = response.data.id
                form.username = response.data.username
                form.real_name = response.data.real_name
                form.nickname = response.data.nickname
                form.mobile = response.data.mobile
                form.email = response.data.email
                form.avatar = response.data.avatar
                form.avatar_url = response.data.avatar_url,
                form.status = response.data.status
                form.created_id = response.data.created_id
                form.created_at = response.data.created_at
                form.updated_id = response.data.updated_id
                form.updated_at = response.data.updated_at
                form.role_id = response.data.role_id
                form.role_name = response.data.role_name
                if (form.avatar_url) {
                    form.avatar_url = '/api/' + form.avatar_url
                }
            } 
        }) 
    }

    //数据详情
    const drawer = ref(false)
    const showDetails = async (id: number) => {
        await getDetails(id);
        drawer.value = true
    }

    
    //获取小仓库中的token
    import useUserStore from "@/store/modules/user";
    import pinia from "@/store";
    let userStore = useUserStore(pinia)
    //定义上传参数
    const uploadParams = {
        key:'admin.avatars',
    }
    //定义上传header头
    const uploadHeaders = {
        token:userStore.token,

    }
    //开始上传文件
    const handleAvatarSuccess: UploadProps['onSuccess'] = (response,uploadFile) => {
        if (response.code == '200') {
            form.avatar_url = '/api' + response.data.path
            form.avatar = response.data.upload_id
        } 
    }


    
</script>

<template>
    <div class="container">
        <div class="search">
            <el-input placeholder="用户名"  v-model="search.username" style="width: 150px;"/>
            <el-input placeholder="手机号"  v-model="search.mobile" style="width: 150px;"/>
            <el-button type="primary" plain  @click="initLists">查询</el-button>
        </div>
        <div class="content">
            <!--按钮区域-->
            <div class="conent-button">
            <el-button type="primary" @click="save(0)">添加</el-button>
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
                    <el-table-column type="selection" width="55" />
                    <el-table-column v-for="(item, _) in tableData.column" 
                    :prop="item['colKey']" :key="item" :label="item['value']" align="center"
                    />

                    <el-table-column label="操作" align="center" width="200px">
                        <template v-slot="scope">
                            <el-button size="small" @click="showDetails(scope.row['id'])">详情</el-button>
                            <el-button size="small" @click="save(scope.row['id'])">编辑</el-button>
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
    <el-dialog :open-delay=300 v-model="dialogFormVisible" :title="dialogTitle" class="dialog-title" style="font-size:10px">
        <el-form 
        :model="form" 
        label-position="left" 
        label-width="150px"
        style="max-width: 500px"
        class="demo-ruleForm"
         >
            <el-form-item label="用户名：" :rules="[{ required: true, message: '用户名不能为空' }]">
                <el-input v-model="form.username" :disabled = "ussernameDisabled" />
            </el-form-item>
            
            <el-form-item label="真实姓名：">
                <el-input v-model="form.real_name" />
            </el-form-item>
            <el-form-item label="昵称：">
                <el-input v-model="form.nickname" />
            </el-form-item>
            <el-form-item label="手机号：" :rules="[{ required: true, message: '手机号不能为空' }]">
                <el-input v-model="form.mobile" />
            </el-form-item>
            <el-form-item label="邮箱：">
                <el-input v-model="form.email" />
            </el-form-item>
            <el-form-item label="角色：" :rules="[{ required: true, message: '角色不能为空' }]">
                <el-select
                    v-model="form.role_id"
                    clearable
                    placeholder="请选择角色"
                    style="width: 240px"
                >
                    <el-option
                    v-for="item in roleList"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id"
                    />
                </el-select>
            </el-form-item>
            <el-form-item label="头像：" >
                <!-- <el-upload><el-button type="primary">选择文件</el-button></el-upload> -->
                <el-upload
                class="avatar-uploader"
                action="/api/admin/upload/index"
                :show-file-list="false"
                :on-success="handleAvatarSuccess"
                :headers="uploadHeaders"
                :data="uploadParams"
                 >
                    <img width="100" height="100" v-if="form.avatar_url" :src="form.avatar_url" class="avatar" />
                    <el-icon v-else class="avatar-uploader-icon"><Plus /></el-icon>
                </el-upload>
            </el-form-item>
            <el-form-item label="状态：">
                <el-radio-group v-model="form.status" class="ml-4">
                    <el-radio :label="1" size="large">启用</el-radio>
                    <el-radio :label="0" size="large">停用</el-radio>
                </el-radio-group>
            </el-form-item>
            <el-form-item label="密码：" >
                <el-button type="primary" plain size="small"  v-if="!form.is_update_pass" @click="form.is_update_pass = 1">重置密码</el-button>
                <el-input v-model="form.password" v-if="form.is_update_pass"/>
            </el-form-item>

           
        </el-form>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogFormVisible = false">取消</el-button>
                <el-button type="primary" @click="submit">保存</el-button>
            </span>
        </template>
    </el-dialog>

    <!--详情数据-->
    <el-drawer
        v-model="drawer"
        title="详情"
        direction="rtl"
        size="38%"
    >
   
    <el-form 
        :model="form" 
        label-position="top" 
        label-width="100px"
        style="max-width: 300px"
         >
            <el-form-item label="用户名：" >
                <span>{{ form.username }}</span>
            </el-form-item>
            <el-form-item label="真实姓名：" >
                <span>{{ form.real_name }}</span> 
            </el-form-item>
            <el-form-item label="昵称：" >
                <span>{{ form.nickname }}</span> 
            </el-form-item>
            <el-form-item label="手机号：">
                <span>{{ form.mobile }}</span> 
            </el-form-item>
            <el-form-item label="角色：">
                <span>{{ form.role_name }}</span> 
            </el-form-item>
            <el-form-item label="邮箱：">
                <span>{{ form.email }}</span> 
            </el-form-item>
            <el-form-item label="头像：" >
                <img width="100" height="100" :src="form.avatar_url"/>
            </el-form-item>

            <el-form-item label="状态：" >
                <span> 启用 </span> 
            </el-form-item>
          
            <el-form-item label="创建时间：">
                <span>{{ form.created_at }}</span>  
            </el-form-item>
            <el-form-item label="修改时间：">
                <span>{{ form.updated_at }}</span> 
            </el-form-item>
           
          

           
        </el-form>
    
    </el-drawer>
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