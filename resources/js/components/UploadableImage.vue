<template>
    <div>
        <img
            src="https://www.nba.com/lakers/sites/lakers/files/1920_lal_mktg_finals_final_wallpaper_jd.jpg"
            alt="Cover photo"
            ref="userImage" 
            class="object-cover w-full"
        />
    </div>
</template>

<script>
import Dropzone from "dropzone";

export default {
    name: "UploadableImage",
    data: () => {
        return {
            dropzone: null
        };
    },

    props: [
        'imageWidth',
        'imageHeight',
        'location'
    ],

    mounted() {
        this.dropzone = new Dropzone(this.$refs.userImage, this.settings);
    },

    computed: {
        settings() {
            return {
                paramName: 'image',
                url: '/api/user-images',
                accepted: 'image/*',
                params: {
                    'width': this.imageWidth,
                    'height': this.imageHeight,
                    'location': this.location,
                },
                headers: {
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content,
                },
                success: (e,res) => {
                    alert('Uploaded');
                }
            };
        }
    },
};
</script>

<style></style>
