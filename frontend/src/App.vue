<template>
  <div class="min-h-screen font-sans py-10 px-4 bg-gradient-to-tr from-indigo-100 via-white to-cyan-100 dark:bg-gradient-to-tr dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100">

    <div class="max-w-3xl mx-auto">
      <div class="flex justify-end mb-4">
        <button
          @click="toggleDarkMode"
          class="text-sm px-3 py-1 rounded border border-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700"
        >
          {{ isDarkMode ? '☀️ Mode clair' : '🌙 Mode sombre' }}
        </button>
      </div>

      <h1 class="text-3xl font-bold text-gray-800 text-center mb-8  dark:text-gray-200">📝 Ma Todo List</h1>

      <div v-if="toastMessage" class="flex items-center gap-3 max-w-xl mx-auto px-4 py-3 rounded-lg shadow transition-all duration-500 mb-6"
        :class="{
        'bg-green-100 border border-green-300 text-green-800 dark:bg-green-900 dark:border-green-700 dark:text-green-200': toastType === 'success',
        'bg-blue-100 border border-blue-300 text-blue-800 dark:bg-blue-900 dark:border-blue-700 dark:text-blue-200': toastType === 'info',
        'bg-red-100 border border-red-300 text-red-800 dark:bg-red-900 dark:border-red-700 dark:text-red-200': toastType === 'error'
        }">
        <!-- Icône animée -->
        <div class="animate-bounce text-xl">
          <span v-if="toastType === 'success'">✅</span>
          <span v-else-if="toastType === 'error'">❌</span>
          <span v-else>ℹ️</span>
        </div>
        <div class="text-base font-semibold leading-snug">
          {{ toastMessage }}
        </div>
      </div>
      <!-- Filtres -->
      <div class="flex justify-center mb-6 gap-4">
        <button
          class="px-4 py-2 rounded text-sm font-medium border transition"
          :class="selectedFilter === 'all'
            ? 'bg-blue-600 text-white border-blue-600'
            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'"
          @click="selectedFilter = 'all'"
        >
          Toutes
        </button>

        <button
          class="px-4 py-2 rounded text-sm font-medium border transition"
          :class="selectedFilter === 'pending'
            ? 'bg-yellow-500 text-white border-yellow-500'
            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'"
          @click="selectedFilter = 'pending'"
        >
          En attente
        </button>

        <button
          class="px-4 py-2 rounded text-sm font-medium border transition"
          :class="selectedFilter === 'completed'
            ? 'bg-green-600 text-white border-green-600'
            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'"
          @click="selectedFilter = 'completed'"
        >
          Terminées
        </button>
      </div>

       <div class="max-w-md mx-auto mb-6">
        <div class="relative">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">
            🔍
          </span>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Rechercher une tâche..."
            class="w-full pl-10 pr-4 py-2 rounded-lg shadow-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400"
          />
        </div>
      </div>


      
      

      <TaskForm v-model="newTask" @submit="createTask" />

      <!-- LISTE DES TÂCHES -->
      <TaskList
        :tasks="filteredTasks"
        @update="updateTask"
        @delete="deleteTask"
      />






    </div>
  </div>
</template>



<script>
import axios from 'axios'
import TaskForm from './components/TaskForm.vue'
import TaskItem from './components/TaskItem.vue'
import TaskList from './components/TaskList.vue'


export default {
  name: 'App',
  components: {
    TaskForm,
    TaskItem,
    TaskList,
  },
  data() {
    return {
      tasks: [],
      newTask: {
        title: '',
        description: '',
        status: 'pending',
      },
      toastMessage: '',
      toastType: 'success',
      selectedFilter: 'all', // all | pending | completed
      searchQuery: '',
      isDarkMode: false,

    }
  },

  mounted() {
    this.getTasks()
  },

  computed: {
    filteredTasks() {
      return this.tasks.filter((task) => {
        const matchFilter =
          this.selectedFilter === 'all' || task.status === this.selectedFilter

        const matchSearch = task.title.toLowerCase().includes(this.searchQuery.toLowerCase())

        return matchFilter && matchSearch
      })
    }
  },


  methods: {
    getTasks() {
      axios
        .get('http://localhost:8000/api/tasks')
        .then((response) => {
          this.tasks = response.data
        })
        .catch((error) => {
          console.error('Erreur lors du chargement des tâches :', error)
        })
    },
    createTask() {
      axios
        .post('http://localhost:8000/api/tasks', this.newTask)
        .then((response) => {
          this.tasks.push(response.data)
          this.showToast('✅ Tâche ajoutée avec succès', 'success')

          this.newTask = {
            title: '',
            description: '',
            status: 'pending',
          }
        })
        .catch((error) => {
          console.error('Erreur lors de la création de la tâche :', error)
          this.showToast('❌ Une erreur est survenue', 'error')
        })
    },
    deleteTask(taskId) {
      axios
        .delete(`http://127.0.0.1:8000/api/tasks/${taskId}`)
        .then(() => {
          this.tasks = this.tasks.filter(task => task.id !== taskId)
          this.showToast('🗑️ Tâche supprimée', 'info')
        })
        .catch((error) => {
          console.error('Erreur lors de la suppression de la tâche :', error)
          this.showToast('❌ Une erreur est survenue', 'error')
        })
    },

    updateTask(taskId, updatedData) {
      axios
        .put(`http://127.0.0.1:8000/api/tasks/${taskId}`, updatedData)
        .then((response) => {
          const index = this.tasks.findIndex((task) => task.id === taskId)
          if (index !== -1) {
            this.tasks[index] = response.data
          }
          this.showToast('💾 Tâche mise à jour', 'success')
        })
        .catch((error) => {
          console.error('Erreur lors de la mise à jour :', error)
          this.showToast('❌ Une erreur est survenue', 'error')
        })
    },

    showToast(message, type = 'success') {
      this.toastMessage = message
      this.toastType = type
      setTimeout(() => {
        this.toastMessage = ''
      }, 3000)
    },

    toggleDarkMode() {
      this.isDarkMode = !this.isDarkMode
      const root = document.documentElement

      if (this.isDarkMode) {
        root.classList.add('dark')
      } else {
        root.classList.remove('dark')
      }
    },

  }
}
</script>
