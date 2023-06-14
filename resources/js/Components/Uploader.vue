<template>
  <div class="flex flex-col gap-2">
    <lr-file-uploader-minimal class="uploader-cfg"
      :css-src="`https://unpkg.com/@uploadcare/blocks@${PACKAGE_VERSION}/web/file-uploader-minimal.min.css`">
      <lr-data-output @lr-data-output="handleUploaderEvent" use-event hidden class="uploader-cfg"></lr-data-output>
    </lr-file-uploader-minimal>

    <div class="grid gap-4 grid-cols-3 w-full max-w-64">
      <img v-for="file in files" :key="file.uuid" :src="`https://ucarecdn.com/${file.uuid}/${file.cdnUrlModifiers || ''
        }-/preview/-/scale_crop/400x400/`" width="200" />
    </div>
  </div>
</template>

<script>
/*
 * Use minified version because codesandbox can't bundle raw css with relative imports.
 * It's better to use '@uploadcare/blocks/blocks/themes/lr-basic/index.css' instead
 */
import "@uploadcare/blocks/web/lr-basic.min.css";
import * as LR from "@uploadcare/blocks";
import { PACKAGE_VERSION } from "@uploadcare/blocks/env";

LR.registerBlocks(LR);

export default {
  name: "Uploader",
  setup() {
    return { PACKAGE_VERSION };
  },
  data() {
    return {
      files: [],
    };
  },
  methods: {
    handleUploaderEvent(e) {
      const { data: files } = e.detail;
      this.files = files;
    },
  },
};
</script>

<style scoped>
.uploader-cfg {
  --ctx-name: "uploader";

  /* DO NOT FORGET TO USE YOUR OWN PUBLIC KEY */
  --cfg-pubkey: "024439cadfc908085f5c";
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


