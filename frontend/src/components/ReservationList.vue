<template>
  <div class="reservation-list">
    <el-card class="box-card">
      <template #header>
        <div class="card-header">
          <span>予約一覧</span>
          <el-button 
            type="primary" 
            @click="$router.push('/create-reservation')"
            :icon="Plus"
          >
            新規予約
          </el-button>
        </div>
      </template>
      
      <el-table 
        :data="reservations" 
        v-loading="loading" 
        stripe 
        style="width: 100%"
        :default-sort="{ prop: 'reservation_date', order: 'ascending' }"
      >
        <el-table-column prop="id" label="予約ID" width="80"></el-table-column>
        
        <el-table-column prop="customer_name" label="お客様名" width="120"></el-table-column>
        
        <el-table-column prop="customer_email" label="メールアドレス" width="200"></el-table-column>
        
        <el-table-column prop="service_type" label="サービス" width="120"></el-table-column>
        
        <el-table-column prop="reservation_date" label="予約日" width="120" sortable></el-table-column>
        
        <el-table-column prop="reservation_time" label="時間" width="80"></el-table-column>
        
        <el-table-column prop="status" label="ステータス" width="100">
          <template #default="scope">
            <el-tag 
              :type="getStatusType(scope.row.status)"
              size="small"
            >
              {{ getStatusText(scope.row.status) }}
            </el-tag>
          </template>
        </el-table-column>
        
        <el-table-column label="操作" width="200">
          <template #default="scope">
            <el-button 
              size="small" 
              type="primary" 
              @click="editReservation(scope.row)"
              :icon="Edit"
            >
              編集
            </el-button>
            <el-button
              size="small"
              type="danger"
              @click="deleteReservation(scope.row)"
              :icon="Delete"
            >
              削除
            </el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-card>

    <el-dialog
      v-model="editDialogVisible"
      title="予約編集"
      width="50%"
      @close="resetEditForm"
    >
      <el-form 
        :model="editForm" 
        :rules="editRules" 
        ref="editFormRef"
        label-width="120px"
      >
        <el-form-item label="お名前" prop="customer_name">
          <el-input v-model="editForm.customer_name"></el-input>
        </el-form-item>
        
        <el-form-item label="メールアドレス" prop="customer_email">
          <el-input v-model="editForm.customer_email" type="email"></el-input>
        </el-form-item>
        
        <el-form-item label="電話番号">
          <el-input v-model="editForm.customer_phone"></el-input>
        </el-form-item>
        
        <el-form-item label="サービス" prop="service_type">
          <el-select v-model="editForm.service_type" style="width: 100%">
            <el-option label="カット" value="カット"></el-option>
            <el-option label="カラーリング" value="カラーリング"></el-option>
            <el-option label="パーマ" value="パーマ"></el-option>
            <el-option label="シャンプー・ブロー" value="シャンプー・ブロー"></el-option>
          </el-select>
        </el-form-item>
        
        <el-form-item label="予約日" prop="reservation_date">
          <el-date-picker
            v-model="editForm.reservation_date"
            type="date"
            format="YYYY-MM-DD"
            value-format="YYYY-MM-DD"
            style="width: 100%"
          ></el-date-picker>
        </el-form-item>
        
        <el-form-item label="予約時間" prop="reservation_time">
          <el-time-select
            v-model="editForm.reservation_time"
            start="09:00"
            step="00:30"
            end="18:00"
            format="HH:mm"
            style="width: 100%"
          ></el-time-select>
        </el-form-item>
        
        <el-form-item label="ステータス">
          <el-select v-model="editForm.status" style="width: 100%">
            <el-option label="保留中" value="pending"></el-option>
            <el-option label="確定" value="confirmed"></el-option>
            <el-option label="キャンセル" value="cancelled"></el-option>
          </el-select>
        </el-form-item>
        
        <el-form-item label="備考">
          <el-input
            v-model="editForm.notes"
            type="textarea"
            :rows="3"
          ></el-input>
        </el-form-item>
      </el-form>
      
      <template #footer>
        <span class="dialog-footer">
          <el-button @click="editDialogVisible = false">キャンセル</el-button>
          <el-button type="primary" @click="updateReservation" :loading="updateLoading">
            更新
          </el-button>
        </span>
      </template>
    </el-dialog>
  </div>
</template>

<script>
import api from '../services/api'
import { ElMessage, ElMessageBox } from 'element-plus'
import { Plus, Edit, Delete } from '@element-plus/icons-vue'

export default {
  name: 'ReservationList',
  components: {
    Plus,
    Edit,
    Delete
  },
  data() {
    return {
      reservations: [],
      loading: false,
      editDialogVisible: false,
      updateLoading: false,
      editForm: {
        id: '',
        customer_name: '',
        customer_email: '',
        customer_phone: '',
        service_type: '',
        reservation_date: '',
        reservation_time: '',
        status: 'pending',
        notes: ''
      },
      editRules: {
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
  async mounted() {
    await this.fetchReservations()
  },
  methods: {
    async fetchReservations() {
      this.loading = true
      try {
        const response = await api.getReservations()
        this.reservations = response.data.data || []
      } catch (error) {
        console.error('予約一覧取得エラー:', error)
        ElMessage.error('予約一覧の取得に失敗しました')
      } finally {
        this.loading = false
      }
    },
    
    getStatusType(status) {
      const statusMap = {
        'pending': 'warning',
        'confirmed': 'success',
        'cancelled': 'danger'
      }
      return statusMap[status] || 'info'
    },
    
    getStatusText(status) {
      const statusMap = {
        'pending': '保留中',
        'confirmed': '確定',
        'cancelled': 'キャンセル'
      }
      return statusMap[status] || status
    },
    
    editReservation(reservation) {
      this.editForm = { ...reservation }
      this.editDialogVisible = true
    },
    
    resetEditForm() {
      this.editForm = {
        id: '',
        customer_name: '',
        customer_email: '',
        customer_phone: '',
        service_type: '',
        reservation_date: '',
        reservation_time: '',
        status: 'pending',
        notes: ''
      }
    },
    
    async updateReservation() {
      this.$refs.editFormRef.validate(async (valid) => {
        if (valid) {
          this.updateLoading = true
          try {
            await api.updateReservation(this.editForm.id, this.editForm)
            ElMessage.success('予約を更新しました')
            this.editDialogVisible = false
            await this.fetchReservations()
          } catch (error) {
            console.error('予約更新エラー:', error)
            ElMessage.error('予約の更新に失敗しました')
          } finally {
            this.updateLoading = false
          }
        }
      })
    },
    
    async deleteReservation(reservation) {
      try {
        await ElMessageBox.confirm(
          `予約ID ${reservation.id} (${reservation.customer_name}様) を削除しますか？`,
          '確認',
          {
            confirmButtonText: '削除',
            cancelButtonText: 'キャンセル',
            type: 'warning',
          }
        )
        
        await api.deleteReservation(reservation.id)
        ElMessage.success('予約を削除しました')
        await this.fetchReservations()
      } catch (error) {
        if (error !== 'cancel') {
          console.error('予約削除エラー:', error)
          ElMessage.error('予約の削除に失敗しました')
        }
      }
    }
  }
}
</script>

<style scoped>
.reservation-list {
  padding: 20px;
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

.dialog-footer {
  text-align: right;
}
</style>