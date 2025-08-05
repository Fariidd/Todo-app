<template>
  <transition-group
    name="fade"
    tag="ul"
    class="space-y-4"
    appear
  >
    <TaskItem
      v-for="task in tasks"
      :key="task.id"
      :task="task"
      @update="onUpdate"
      @delete="onDelete"
    />
  </transition-group>
</template>

<script>
import TaskItem from './TaskItem.vue'

export default {
    name: 'TaskList',
    props: {
        tasks: Array,
    },

    components: {
        TaskItem
    },

    emits: ['update', 'delete'],


    methods: {
        onUpdate(id, updatedTask) {
        this.$emit('update', id, updatedTask)
        },
        onDelete(id) {
        this.$emit('delete', id)
        }
    }
}
</script>