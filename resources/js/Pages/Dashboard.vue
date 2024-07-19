<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import TaskApp from "@/Components/TaskApp.vue";

// State variable to control visibility of TaskApp
const showTaskApp = ref(false);

const toggleTaskApp = () => {
  //   showTaskApp.value = !showTaskApp.value;
  showTaskApp.value = true;
};
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center space-x-4 p-6 bg-gray-100">
        <h2
          class="text-sky-400 font-semibold text-xl text-gray-800 leading-tight"
        >
          PHP- Simple To Do List App
        </h2>
      </div>
    </template>

    <div class="py-4">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-1 flex space-x-2">
            <div class="flex-1">
              <TaskApp />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Additional styling if needed */
</style>
