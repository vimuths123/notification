<template>
  <div>
    <h1 style="font-weight: bold; margin-bottom: 10px" class="font-bold mb-10">{{ project.name }}</h1>
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

const props = defineProps(['project']);

function loadTasks() {
  const response = axios
    .get("/projects/" + props.project.id + "/tasks")
    .then((response) => (tasks.value = response.data));

  window.Echo.private("tasks." + props.project.id).listen("TaskCreated", (e) => {
    tasks.value.push(e.task.body);
  });
}

const addTask = () => {
  // axios.post("/tasks", { body: newTask.value });
  axios.post("/projects/" + props.project.id + "/tasks", { body: newTask.value });
  tasks.value.push(newTask.value);  
  newTask.value = "";
};
onMounted(loadTasks);
</script>