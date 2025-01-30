<script>
export default {
  props: ['rooms', 'currentRoom'],
  data() {
    return {
      selected: this.currentRoom // Bind `selected` to `currentRoom` initially
    };
  },
  watch: {
    selected(newRoom) {
      // Emit event whenever the selected room changes
      this.$emit('roomchanged', newRoom);
    }
  }
};
</script>

<template>
  <div class="grid grid-cols-2">
    <div class="font-bold text-xl">
      {{ selected.name }} Chat
    </div>
    <div>
      <select v-model="selected" class="float-right">
        <option v-for="(room, index) in rooms" :key="index" :value="room">
          {{ room.name }}
        </option>
      </select>
    </div>
  </div>
</template>
