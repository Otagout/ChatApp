<script>

export default{
    props:['message']
}
</script>

<template>
<div>
    {{ message.user.name }} :{{ message.message }}
</div>
</template>