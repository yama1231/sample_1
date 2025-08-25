<template>
  <div class="create-reservation">
    <el-card class="box-card">
      <template #header>
        <div class="card-header">
          <span>新規予約</span>
        </div>
      </template>
      
      <el-form 
        :model="reservationForm" 
        :rules="rules" 
        ref="reservationFormRef" 
        label-width="120px"
        class="reservation-form"
      >
        <el-form-item label="お名前" prop="customer_name">
          <el-input 
            v-model="reservationForm.customer_name" 
            placeholder="お名前を入力してください"
          ></el-input>
        </el-form-item>
        
        <el-form-item label="メールアドレス" prop="customer_email">
          <el-input 
            v-model="reservationForm.customer_email" 
            type="email"
            placeholder="メールアドレスを入力してください"
          ></el-input>
        </el-form-item>
        
        <el-form-item label="電話番号" prop="customer_phone">
          <el-input 
            v-model="reservationForm.customer_phone" 
            placeholder="電話番号を入力してください"
          ></el-input>
        </el-form-item>
        
        <el-form-item label="サービス" prop="service_type">
          <el-select 
            v-model="reservationForm.service_type" 
            placeholder="サービスを選択してください"
            style="width: 100%"
          >
            <el-option
              v-for="service in services"
              :key="service.name"
              :label="service.name"
              :value="service.name"
            >
              <span>{{ service.name }}</span>
              <span style="float: right; color: #8492a6; font-size: 13px">
                {{ service.duration }}分 / ¥{{ service.price }}
              </span>
            </el-option>
          </el-select>
        </el-form-item>
        
        <el-form-item label="予約日" prop="reservation_date">
          <el-date-picker
            v-model="reservationForm.reservation_date"
            type="date"
            placeholder="予約日を選択してください"
            :disabled-date="disabledDate"
            format="YYYY-MM-DD"
            value-format="YYYY-MM-DD"
            style="width: 100%"
          ></el-date-picker>
        </el-form-item>
        
        <el-form-item label="予約時間" prop="reservation_time">
          <el-time-select
            v-model="reservationForm.reservation_time"
            start="09:00"
            step="00:30"
            end="18:00"
            placeholder="予約時間を選択してください"
            format="HH:mm"
            style="width: 100%"
          ></el-time-select>
        </el-form-item>
        
        <el-form-item label="備考">
          <el-input
            v-model="reservationForm.notes"
            type="textarea"
            :rows="3"
            placeholder="ご要望やご質問があればお書きください"
          ></el-input>
        </el-form-item>
        
        <el-form-item>
          <el-button 
            type="primary" 
            @click="submitForm"
            :loading="loading"
            size="large"
          >
            予約する
          </el-button>
          <el-button @click="resetForm" size="large">リセット</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import api from '../services/api'
import { ElMessage } from 'element-plus'

export default {
  name: 'CreateReservation',
  data() {
    return {
      loading: false,
      services: [
        { name: 'カット', duration: 60, price: 3000 },
        { name: 'カラーリング', duration: 120, price: 5000 },
        { name: 'パーマ', duration: 180, price: 8000 },
        { name: 'シャンプー・ブロー', duration: 30, price: 1500 }
      ],
      reservationForm: {
        customer_name: '',
        customer_email: '',
        customer_phone: '',
        service_type: '',
        reservation_date: '',
        reservation_time: '',
        notes: ''
      },
      rules: {
        customer_name: [
          { required: true, message: 'お名前を入力してください', trigger: 'blur' }
        ],
        customer_email: [
          { required: true, message: 'メールアドレスを入力してください', trigger: 'blur' },
          { type: 'email', message: '正しいメールアドレスを入力してください', trigger: 'blur' }
        ],
        service_type: [
          { required: true, message: 'サービスを選択してください', trigger: 'change' }
        ],
        reservation_date: [
          { required: true, message: '予約日を選択してください', trigger: 'change' }
        ],
        reservation_time: [
          { required: true, message: '予約時間を選択してください', trigger: 'change' }
        ]
      }
    }
  },
  methods: {
    disabledDate(date) {
      return date < new Date()
    },
    
    async submitForm() {
      this.$refs.reservationFormRef.validate(async (valid) => {
        if (valid) {
          this.loading = true
          try {
            await api.createReservation(this.reservationForm)
            ElMessage.success('予約が完了しました')
            this.resetForm()
            this.$router.push('/reservations')
          } catch (error) {
            console.error('予約エラー:', error)
            ElMessage.error('予約に失敗しました。もう一度お試しください。')
          } finally {
            this.loading = false
          }
        }
      })
    },
    
    resetForm() {
      this.$refs.reservationFormRef.resetFields()
    }
  }
}
</script>

<style scoped>
.create-reservation {
  max-width: 600px;
  margin: 0 auto;
}

.box-card {
  margin: 20px 0;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 18px;
  font-weight: bold;
}

.reservation-form {
  padding: 20px;
}
</style>