<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-4 rounded max-w-md w-full">
      <h2 class="text-xl mb-4">{{ comic ? 'Edit' : 'Add' }} Comic</h2>
      
      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div>
          <label class="block mb-1" style="color: rgb(25, 23, 17)">Title</label>
          <input 
            v-model="formData.title"
            type="text"
            class="w-full border rounded p-2"
            required
          >
        </div>

        <div>
          <label class="block mb-1" style="color: rgb(25, 23, 17)">Author</label>
          <input 
            v-model="formData.author"
            type="text"
            class="w-full border rounded p-2"
            required
          >
        </div>

        <div>
          <label class="block mb-1" style="color: rgb(25, 23, 17)">Description</label>
          <textarea 
            v-model="formData.description"
            class="w-full border rounded p-2"
            rows="3"
            required
          ></textarea>
        </div>

        <div>
          <label class="block mb-1" style="color: rgb(25, 23, 17)">Genre</label>
          <input 
            v-model="formData.genre"
            type="text"
            class="w-full border rounded p-2"
            required
          >
        </div>

        <div>
          <label class="block mb-1" style="color: rgb(25, 23, 17)">Price ($)</label>
          <input 
            v-model.number="formData.price"
            type="number"
            step="0.01"
            min="0"
            class="w-full border rounded p-2"
            placeholder="0.00"
          >
        </div>

        <div>
          <label class="block mb-1" style="color: rgb(25, 23, 17)">Status</label>
          <select 
            v-model="formData.status"
            class="w-full border rounded p-2"
            required
          >
            <option value="published">Published</option>
            <option value="draft">Draft</option>
          </select>
        </div>

        <div class="flex justify-end space-x-2">
          <button 
            type="button"
            @click="$emit('close')"
            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
          >
            Cancel
          </button>
          <button 
            type="submit"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
          >
            {{ comic ? 'Update' : 'Create' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  comic: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'submit'])

const formData = ref({
  title: '',
  author: '',
  description: '',
  genre: '',
  status: 'published',
  price: 0
})

const handleSubmit = () => {
  const submissionData = {
    ...formData.value,
    price: parseFloat(formData.value.price) || 0
  };
  emit('submit', submissionData)
}

onMounted(() => {
  if (props.comic) {
    formData.value = {
      ...formData.value,
      ...props.comic,
      price: parseFloat(props.comic.price) || 0
    }
  }
})
</script>