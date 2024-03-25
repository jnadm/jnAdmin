import { ElMessage } from 'element-plus'

const message = {
    success() {
        ElMessage({
            message: '操作成功',
            type: 'success',
            duration: 1500,
        });
    }
}
export default message;