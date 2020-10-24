<template>
    <div class="bg-white rounded shadow w-2/3 p-4">
       <div class="flex justify-between items-center">
        <div class="w-8">
            <img class="w-8 h-8 object-cover rounded-full" src="https://world-celebs.com/public/media/celebrity/2019/07/13/0hoybydh3lax-joji-filthy-frank.jpg" alt="profile pic">
        </div>

        <div class="flex-1 mx-2">
            <input class="w-full h-8 pl-4 bg-gray-200 rounded-full focus:outline-none focus:shadow-outline text-sm" type="text" name="body" id="body" placeholder="What's on your mind?">
        </div>
        <div>
              <button class="flex justify-center items-center bg-gray-200 rounded-full w-10 h-10 text-gray-600 focus:outline-none focus:shadow-outline">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="fill-current w-5 h-5"><path d="M21.8 4H2.2c-.2 0-.3.2-.3.3v15.3c0 .3.1.4.3.4h19.6c.2 0 .3-.1.3-.3V4.3c0-.1-.1-.3-.3-.3zm-1.6 13.4l-4.4-4.6c0-.1-.1-.1-.2 0l-3.1 2.7-3.9-4.8h-.1s-.1 0-.1.1L3.8 17V6h16.4v11.4zm-4.9-6.8c.9 0 1.6-.7 1.6-1.6 0-.9-.7-1.6-1.6-1.6-.9 0-1.6.7-1.6 1.6.1.9.8 1.6 1.6 1.6z"/></svg>
            </button>
        </div>
       </div>
    </div>
</template>

<script>
export default {
    name: "NewPost",
}
</script>

<style>

</style>