<template>
  <div id="app">
    <el-container>
      <el-header height="80px" style="background-color: #409EFF; color: white;">
        <h1 style="margin: 0; line-height: 80px; text-align: center;">予約システム</h1>
      </el-header>
      
      <el-main>
        <el-menu 
          :default-active="$route.path" 
          mode="horizontal" 
          @select="handleSelect"
          style="margin-bottom: 20px;"
        >
          <el-menu-item index="/reservations">予約一覧</el-menu-item>
          <el-menu-item index="/create-reservation">新規予約</el-menu-item>
        </el-menu>
        
        <router-view />
      </el-main>
    </el-container>
  </div>
</template>

<script>
export default {
  name: 'App',
  methods: {
    handleSelect(key) {
      this.$router.push(key)
    }
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}

body {
  margin: 0;
  padding: 0;
}
</style>