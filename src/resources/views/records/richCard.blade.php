<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "MusicAlbum",
  "@id": "{{ app_url() . '/records/' . $record->id }}",
  "url": "{{ app_url() . '/records/' . $record->id }}",
  "name": "{{$record->title}}",
  "byArtist": {
    "@type": "MusicGroup",
    "name": "{{ $record->artist }}"
  },
  "albumRelease": {
        "@type": "MusicRelease",
        "name": "{{ $record->title }}",
        "@id": "{{ app_url() . '/records/' . $record->id }}"
    },
  "recordLabel": {
        "@type": "Organization",
        "name": "{{ $record->label }}"
    },
    @if($record->thumb)
        "image": "{{$record->thumb}}",
    @endif
    "catalogNumber": "{{ $record->catalog_no }}"

}
</script>