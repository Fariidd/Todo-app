<template>
  <form @submit.prevent="emitSubmit" class="space-y-4 mb-6">
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Titre :</label>
      <input
        v-model="localTask.title"
        @input="$emit('update:modelValue', localTask)"
        type="text"
        required
        class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
      />
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Description :</label>
      <input
        v-model="localTask.description"
        @input="$emit('update:modelValue', localTask)"
        type="text"
        required
        class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
      />
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Statut :</label>
      <select
        v-model="localTask.status"
        @change="$emit('update:modelValue', localTask)"
        class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
      >
        <option value="pending">⏳ En cours</option>
        <option value="completed">✅ Terminée</option>
      </select>
    </div>

    <button
      type="submit"
      class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600"
    >
      ➕ Ajouter la tâche
    </button>

  </form>
</template>


<script>
  export default {
    name: 'TaskForm',
    props: {
      modelValue: Object
    },
    emits: ['update:modelValue', 'submit'],
    data() {
      return {
        localTask: { ...this.modelValue }
      }
    },
    watch: {
      modelValue(newValue) {
        this.localTask = { ...newValue }
      }
    },
    methods: {
      emitSubmit() {
        this.$emit('submit')
      }
    }
  }
</script>

