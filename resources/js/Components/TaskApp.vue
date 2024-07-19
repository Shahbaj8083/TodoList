<template>
  <div class="p-4 bg-gray-100 min-h-screen">
    <div class="space-y-4">
      <div class="flex items-center space-x-4 mb-4">
        <input
          v-model="newTaskName"
          type="text"
          placeholder="Enter new task"
          class="flex-1 p-2 border rounded"
        />
        <button
          @click="createTask"
          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
        >
          Create Task
        </button>
      </div>

      <!-- Error Message -->
      <div v-if="errorMessage" class="mb-4 text-red-500">
        {{ errorMessage }}
      </div>

      <!-- Task List Header -->
      <div class="flex items-center bg-gray-200 p-2 rounded-lg">
        <div class="flex-1 text-center">#</div>
        <div class="flex-1 text-center">Task</div>
        <div class="flex-1 text-center">Status</div>
        <div class="flex-1 text-center">Actions</div>
      </div>

      <!-- Task List -->
      <div
        v-for="(task, index) in filteredTasks"
        :key="task.id"
        class="flex items-center bg-white shadow-md rounded-lg p-4"
      >
        <div class="flex-1 text-center">{{ index + 1 }}</div>
        <div
          :class="{ 'text-gray-500': task.completed }"
          class="flex-1 text-center"
        >
          {{ task.name }}
        </div>
        <div class="flex-1 text-center">
          {{ task.completed ? "Done" : "" }}
        </div>
        <div class="flex-1 flex justify-center space-x-2">
          <button
            @click="confirmDelete(task)"
            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition"
          >
            Delete
          </button>
          <input
            v-if="!task.completed"
            type="checkbox"
            @change="toggleTask(task)"
            :checked="task.completed"
            class="form-checkbox h-5 w-5 text-blue-500 rounded"
          />
        </div>
      </div>

      <!-- Show All Tasks Button -->
      <button
        @click="toggleShowAll"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
      >
        {{ showAll ? "Show Incomplete Tasks" : "Show All Tasks" }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";

// Reactive state to manage tasks, new task name, error message, and visibility
const tasks = ref([]);
const newTaskName = ref("");
const errorMessage = ref("");
const showAll = ref(false);

// Fetch tasks from the API
const fetchTasks = async () => {
  try {
    const response = await axios.get("/api/tasks");
    tasks.value = response.data;
  } catch (error) {
    console.error("Error fetching tasks:", error);
  }
};

// Computed property to filter tasks based on visibility state
const filteredTasks = computed(() => {
  return showAll.value
    ? tasks.value
    : tasks.value.filter((task) => !task.completed);
});

// Method to toggle task completion
const toggleTask = async (task) => {
  try {
    // Update task completion status on the server
    await axios.patch(`/api/tasks/${task.id}`, { completed: !task.completed });

    // Update the local task status
    task.completed = !task.completed;
  } catch (error) {
    console.error("Error updating task:", error);
  }
};

// Method to confirm and delete task
const confirmDelete = async (task) => {
  if (confirm("Are you sure you want to delete this task?")) {
    try {
      await axios.delete(`/api/tasks/${task.id}`);
      tasks.value = tasks.value.filter((t) => t.id !== task.id);
    } catch (error) {
      console.error("Error deleting task:", error);
    }
  }
};

// Method to create a new task
const createTask = async () => {
  if (newTaskName.value.trim() === "") {
    alert("Task name cannot be empty");
    return;
  }

  try {
    const response = await axios.post("/api/tasks", {
      name: newTaskName.value,
    });
    tasks.value.push(response.data);
    newTaskName.value = "";
    errorMessage.value = ""; // Clear any previous error message
  } catch (error) {
    if (error.response && error.response.data.errors) {
      errorMessage.value = error.response.data.errors.name[0];
    } else {
      console.error("Error creating task:", error);
    }
  }
};

// Method to toggle the visibility of all tasks
const toggleShowAll = () => {
  showAll.value = !showAll.value;
};

// Fetch tasks when the component is mounted
onMounted(() => {
  fetchTasks();
});
</script>

<style scoped>
/* Optional custom styling for additional effects */
.completed {
  text-decoration: line-through;
  color: #6b7280; /* Tailwind's gray-500 */
}
</style>
