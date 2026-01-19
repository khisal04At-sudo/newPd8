@component('mail::message')
# مستندات إضافية مطلوبة لاعتماد مؤسستكم

## مؤسسة: {{ $organization->name }}

بعد مراجعة طلب مؤسستكم، نحتاج إلى بعض المستندات الإضافية لإكمال عملية الاعتماد.

### المستندات المطلوبة

{{ $requestedDocuments }}

### كيفية رفع المستندات

@component('mail::button', ['url' => route('organization.verify.documents')])
رفع المستندات المطلوبة
@endcomponent

يرجى تحميل المستندات المطلوبة في أقرب وقت ممكن حتى نتمكن من إكمال عملية الاعتماد.

مع تحيات،<br>
{{ config('app.name') }}
@endcomponent
