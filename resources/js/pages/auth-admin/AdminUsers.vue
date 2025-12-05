<template>
  <AdminIndex>
    <h1>Users Test</h1>
    <pre>{{ users }}</pre>
    <table border="1">
      <thead>
        <tr>
          <th>ID</th><th>Name</th><th>Email</th><th>Role</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
        </tr>
      </tbody>
    </table>
  </AdminIndex>
</template>

<script setup lang="ts">
import AdminIndex from '../AdminIndex.vue' 

const props = defineProps<{ users: Array<{id:number,name:string,email:string,role:string}> }>()
const users = props.users
</script>

