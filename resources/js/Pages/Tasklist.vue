<template>
  <div>
    <ul>
      <li v-for="task in tasks" :key="task" v-text="task"></li>
    </ul>

    <input type="text" v-model="newTask" @blur="addTask" />
  </div>
</template>
<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";

const tasks = ref([]);
const newTask = ref("");

function loadTasks() {
  const response = axios
    .get("/tasks")
    .then((response) => (tasks.value = response.data));

  window.Echo.channel("tasks").listen("TaskCreated", (e) => {
    tasks.value.push(e.task.body);
  });
}

const addTask = () => {
  axios.post("/tasks", { body: newTask.value });
  tasks.value.push(newTask.value);  
  newTask.value = "";
};
onMounted(loadTasks);
</script>