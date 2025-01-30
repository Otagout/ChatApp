<script>
import axios from 'axios';

export default {
    props: ['room'], // Receiving the room as a prop

    data() {
        return {
            message: '' // Store the message in the input field
        };
    },

    methods: {
        sendMessage() {
            // Trim the message to avoid sending empty or space-only messages
            if (this.message.trim() === '') {
                return;
            }

            // Make an axios POST request to send the message
            axios.post(`/chat/room/${this.room.id}/message`, {
                message: this.message
            })
            .then(response => {
                if (response.status === 201) {
                    this.message = ''; // Clear the input field after sending
                    this.$emit('messageSent'); // Emit event to notify the parent component
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
            });
        }
    }
};
</script>

<template>
 <div class="relative h-10 m-1">
    <div class=" grid grid-cols-6" style="border-top: 1px solid #e6e6e6; ">
        <input type="text" v-model="message" name=""
         @keyup.enter="sendMessage()"  
         placeholder="Say something" class="col-span-5 outline-none p-1">
      <button @click="sendMessage()" 
        class="place-self-end-bg-grey-500 hover:bg-blue p-1 mt-1 rounded text-white">
        Send
    </button>
        </div>
 </div>
</template>