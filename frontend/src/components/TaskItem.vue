<template>
  <li class="border p-4 rounded-md shadow-sm bg-white dark:bg-gray-800 dark:border-gray-700 flex flex-col gap-2">
    <!-- Mode édition -->
    <div v-if="editing" class="space-y-2">
        <input
            v-model="editedTask.title"
            placeholder="Titre"
            class="w-full px-4 py-2 rounded-md border shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600"
        />

        <input
            v-model="editedTask.description"
            placeholder="Description"
            class="w-full px-4 py-2 rounded-md border shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600"
        />

        <select v-model="editedTask.status" class="w-full px-4 py-2 rounded-md border shadow-sm bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <option value="pending">🕓 En attente</option>
            <option value="completed">✅ Terminée</option>
        </select>

        <div class="flex gap-2 mt-2">
            <button
            @click="save"
            class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition"
            >
            💾 Enregistrer
            </button>
            <button
            @click="cancel"
            class="bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition"
            >
            Annuler
            </button>
        </div>
    </div>


    <!-- Mode affichage -->
    <div v-else>
      <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ task.title }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">{{ task.description }}</p>
            
            <p class="text-sm mt-1 font-medium" :class="task.status === 'completed' ? 'text-green-700 dark:text-green-400' : 'text-yellow-700 dark:text-yellow-300'">
                <span v-if="task.status === 'completed'">✅ Terminée</span>
                <span v-else>🕓 En cours</span>
            </p>


            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                🗓️ Ajoutée le {{ formatDate(task.createdAt) }}
            </p>
        </div>
        <div class="flex gap-2">
          <button @click="edit" class="text-blue-600 hover:underline">✏️ Modifier</button>
          <button @click="$emit('delete', task.id)" class="text-red-600 hover:underline">🗑️ Supprimer</button>
        </div>
      </div>
    </div>
  </li>
</template>



<script>
export default {
  name: 'TaskItem',
  props: {
    task: Object,
  },
  data() {
    return {
      editing: false,
      editedTask: {
        title: '',
        description: '',
        status: 'pending',
      },
    }
  },
  methods: {
    edit() {
      this.editedTask = { ...this.task }
      this.editing = true
    },
    cancel() {
      this.editing = false
    },
    save() {
      this.$emit('update', this.task.id, this.editedTask)
      this.editing = false
    },
    formatDate(dateString) {
        const date = new Date(dateString)
        return date.toLocaleString('fr-FR', {
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        })
    },
  },

}
</script>
