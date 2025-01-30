<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import MessageContainer from './messageContainer.vue';
import inputMessage from './inputMessage.vue';
import axios from 'axios';
import ChatRoomSelection from './chatRoomSelection.vue';
import Pusher from 'pusher-js';

export default {
  components: {
    AppLayout,
    MessageContainer,
    inputMessage,
    ChatRoomSelection
  },
  data() {
    return {
      chatRooms: [], // All available chat rooms
      currentRoom: null, // Currently selected room
      messages: [], // Combined messages (database + real-time)
      pusherInstance: null, // Pusher instance
      channel: null // Current Pusher channel
    };
  },
  watch: {
    currentRoom(newRoom, oldRoom) {
      // Handle room change
      if (oldRoom) {
        this.disconnect();
      }
      if (newRoom) {
        this.getMessages();
        this.connect();
      }
    }
  },
  methods: {
    // Fetch chat rooms
    getRooms() {
      axios.get('/chat/rooms')
        .then((response) => {
          this.chatRooms = response.data;
          if (this.chatRooms.length > 0) {
            this.setRoom(this.chatRooms[0]);
          }
        })
        .catch((error) => {
          console.error('Error fetching rooms:', error);
        });
    },

    // Fetch messages for the current room
    getMessages() {
      if (!this.currentRoom) return;

      axios.get(`/chat/room/${this.currentRoom.id}/messages`)
        .then((response) => {
          this.messages = response.data; // Replace messages with server data
        })
        .catch((error) => {
          console.error('Error fetching messages:', error);
        });
    },

    // Set the current room
    setRoom(room) {
      this.currentRoom = room;
    },

    // Connect to the current room's Pusher channel
    connect() {
      if (!this.currentRoom) return;

      // Initialize Pusher if not already initialized
      if (!this.pusherInstance) {
        this.pusherInstance = new Pusher('4e73dda8857c6fcf4622', {
          cluster: 'eu',
          encrypted: true
        });
      }

      // Subscribe to the channel for the current room
      this.channel = this.pusherInstance.subscribe(`chat.${this.currentRoom.id}`);

      // Listen for new messages
      this.channel.bind('App\\Events\\NewChatMessage', (data) => {
        this.messages.unshift(data);
        this.scrollToBottom();
      });
    },

    // Disconnect from the current room
    disconnect() {
      if (this.channel) {
        this.pusherInstance.unsubscribe(`chat.${this.currentRoom.id}`);
        this.channel = null;
      }
    },

    // Scroll to the bottom of the chat
    scrollToBottom() {
      this.$nextTick(() => {
        const container = this.$refs.messageContainer;
        if (container) {
          container.scrollTop = container.scrollHeight;
        }
      });
    }
  },
  created() {
    this.getRooms(); // Fetch rooms on component creation
  },
  beforeDestroy() {
    this.disconnect(); // Clean up Pusher subscriptions
  }
};
</script>
<template>
  <AppLayout title="Chat">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <ChatRoomSelection
          v-if="currentRoom"
          :rooms="chatRooms"
          :currentRoom="currentRoom"
          @roomchanged="setRoom"
        />
      </h2>
    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="h-96 w-full">
            <div
              ref="messageContainer"
              class="h-full p-2 flex flex-col-reverse overflow-y-scroll"
            >
              <div
                v-for="(message, index) in messages"
                :key="index"
              >
                {{ message.user.name }}: {{ message.message }}
              </div>
            </div>
          
          </div>  <inputMessage
              @messageSent="getMessages"
              :room="currentRoom"
            />          
        </div>
      </div>
    </div>
  </AppLayout>
</template>
