 <script setup lang='ts'>
   import {
    ArrowRight,
    CaretBottom,
    CaretTop,
    Warning,
    } from '@element-plus/icons-vue'
    import * as echarts from 'echarts';

    import { onMounted } from "vue";

//声明周期函数，自动执行初始化
onMounted(() => {
  init();
});
//初始化函数
function init() {
  // 基于准备好的dom，初始化echarts实例
  let Chart = echarts.init(document.getElementById("main"), 'roma');
  // 绘制图表
  let options = {
    backgroundColor: "#fff",
    xAxis: {
        type: 'category',
        data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
    },
    yAxis: {
        type: 'value'
    },
    series: [
        {
        data: [120, 200, 150, 80, 70, 110, 130],
        type: 'bar'
        }
    ]
    };
  // 渲染图表
  Chart.setOption(options);
}


 </script>
 
 <template>
     <div class="one">
        <el-row :gutter="16">
            <el-col :span="8">
            <div class="statistic-card">
                <el-statistic :value="98500">
                <template #title>
                    <div style="display: inline-flex; align-items: center">
                    日活跃用户
                    <el-tooltip
                        effect="dark"
                        content="Number of users who logged into the product in one day"
                        placement="top"
                    >
                        <el-icon style="margin-left: 4px" :size="12">
                        <Warning />
                        </el-icon>
                    </el-tooltip>
                    </div>
                </template>
                </el-statistic>
                <div class="statistic-footer">
                <div class="footer-item">
                    <span>than yesterday</span>
                    <span class="green">
                    18%
                    <el-icon>
                        <CaretTop />
                    </el-icon>
                    </span>
                </div>
                </div>
            </div>
            </el-col>
            <el-col :span="8">
            <div class="statistic-card">
                <el-statistic :value="693700">
                <template #title>
                    <div style="display: inline-flex; align-items: center">
                    月活跃用户
                    <el-tooltip
                        effect="dark"
                        content="Number of users who logged into the product in one month"
                        placement="top"
                    >
                        <el-icon style="margin-left: 4px" :size="12">
                        <Warning />
                        </el-icon>
                    </el-tooltip>
                    </div>
                </template>
                </el-statistic>
                <div class="statistic-footer">
                <div class="footer-item">
                    <span>month on month</span>
                    <span class="red">
                    12%
                    <el-icon>
                        <CaretBottom />
                    </el-icon>
                    </span>
                </div>
                </div>
            </div>
            </el-col>
            <el-col :span="8">
            <div class="statistic-card">
                <el-statistic :value="72000" title="New transactions today">
                <template #title>
                    <div style="display: inline-flex; align-items: center">
                    今日新增交易数
                    </div>
                </template>
                </el-statistic>
                <div class="statistic-footer">
                <div class="footer-item">
                    <span>than yesterday</span>
                    <span class="green">
                    16%
                    <el-icon>
                        <CaretTop />
                    </el-icon>
                    </span>
                </div>
                <div class="footer-item">
                    <el-icon :size="14">
                    <ArrowRight />
                    </el-icon>
                </div>
                </div>
            </div>
            </el-col>
        </el-row>
    </div>
    <div id="main"></div>

 </template>
 
 <style lang='scss' scoped>
        .one{
            margin-top: 20px;
        }
        :global(h2#card-usage ~ .example .example-showcase) {
            background-color: var(--el-fill-color) !important;
        }

        .el-statistic {
            --el-statistic-content-font-size: 28px;
        }

        .statistic-card {
            height: 100%;
            padding: 20px;
            border-radius: 4px;
            background-color: var(--el-bg-color-overlay);
        }

        .statistic-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            font-size: 12px;
            color: var(--el-text-color-regular);
            margin-top: 16px;
        }

        .statistic-footer .footer-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .statistic-footer .footer-item span:last-child {
            display: inline-flex;
            align-items: center;
            margin-left: 4px;
        }

        .green {
            color: var(--el-color-success);
        }
        .red {
            color: var(--el-color-error);
        }
       
        #main {
            margin-top: 30px;
            width: 82.8vw;
            height: 60vh;
        }
 </style>