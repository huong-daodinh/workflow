<template>
  <img
    :src="`/assets/images/file_icons/${fileIcon}.svg`"
    class="h-7 w-7 mr-1 inline"
    alt="file_icon" />
</template>

<script lang="ts" setup>
import { defineProps, ref } from 'vue';
const extensionMatch = {
  text: 'txt',
  txt: 'txt',
  pdf: 'pdf',
  doc: 'word',
  docx: 'word',
  xls: 'excel',
  xlsx: 'excel',
  ppt: 'ppt',
  pptx: 'ppt',
  jpg: 'img',
  jpeg: 'img',
  png: 'img',
  gif: 'img',
  mp4: 'video',
  zip: 'zip',
  '7zip': 'zip',
  rar: 'zip'
};
const props = defineProps({
  extension: {
    type: String,
    default: 'file'
  }
});
const fileIcon = ref(extensionMatch[props.extension] || 'file');
</script>
