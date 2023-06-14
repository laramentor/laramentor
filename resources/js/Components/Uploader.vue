<script setup>
import "@uploadcare/blocks/web/lr-basic.min.css";
import * as LR from "@uploadcare/blocks";
import { PACKAGE_VERSION } from "@uploadcare/blocks/env";
import { defineProps, defineEmits } from "vue";

LR.registerBlocks(LR);

const api_key = import.meta.env.VITE_UPLOADCARE_PUBLIC_KEY;

const emit = defineEmits(['update:modelValue']);

const handleUploaderEvent = (e) => {
  const { data: files } = e.detail;

  emit("update:modelValue", files);
};

defineProps({
  modelValue: {
    type: Array
  },
});

</script>

<template>
  <div class="flex flex-col gap-2">
    <lr-file-uploader-minimal class="uploader-cfg"
      :style="`--cfg-pubkey: '${api_key}';`"
      :css-src="`https://unpkg.com/@uploadcare/blocks@${PACKAGE_VERSION}/web/file-uploader-minimal.min.css`">
      <lr-data-output @lr-data-output="handleUploaderEvent" use-event hidden class="uploader-cfg"></lr-data-output>
    </lr-file-uploader-minimal>
  </div>
</template>

<style scoped>
.uploader-cfg {
  --ctx-name: "uploader";

  /* DO NOT FORGET TO USE YOUR OWN PUBLIC KEY */
  --cfg-img-only: 1;
  --cfg-multiple: 0;
  --cfg-max-local-file-size-bytes: 10485760;
  --cfg-multiple-max: 1;
  --darkmode: 0;
  --h-accent: 244;
  --s-accent: 55%;
  --l-accent: 41%;
}
</style>
