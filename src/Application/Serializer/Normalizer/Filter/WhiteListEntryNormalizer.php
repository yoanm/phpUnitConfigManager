<?php
namespace Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Filter;

use Yoanm\PhpUnitConfigManager\Application\Serializer\Helper\NodeNormalizerHelper;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\AttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NodeWithAttributeNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\DenormalizerInterface;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\FilesystemItemNormalizer;
use Yoanm\PhpUnitConfigManager\Application\Serializer\Normalizer\Common\NormalizerInterface;
use Yoanm\PhpUnitConfigManager\Domain\Model\Common\FilesystemItem;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\ExcludedWhiteList;
use Yoanm\PhpUnitConfigManager\Domain\Model\Filter\WhiteListItem;

class WhiteListEntryNormalizer extends NodeWithAttributeNormalizer implements
    DenormalizerInterface,
    NormalizerInterface
{
    /**
     * @param NodeNormalizerHelper     $nodeNormalizerHelper
     * @param AttributeNormalizer      $attributeNormalizer
     * @param FilesystemItemNormalizer $filesystemItemNormalizer
     */
    public function __construct(
        NodeNormalizerHelper $nodeNormalizerHelper,
        AttributeNormalizer $attributeNormalizer,
        FilesystemItemNormalizer $filesystemItemNormalizer
    ) {
        parent::__construct(
            $nodeNormalizerHelper,
            $attributeNormalizer,
            [
                $filesystemItemNormalizer
            ]
        );
    }

    /**
     * @param WhiteListItem|ExcludedWhiteList $item
     * @param \DOMDocument                    $document
     *
     * @return \DOMElement
     */
    public function normalize($item, \DOMDocument $document)
    {
        return $this->getNormalizer($item)->normalize($item, $document);
    }

    /**
     * @param \DOMNode $node
     *
     * @return WhiteListItem|ExcludedWhiteList
     */
    public function denormalize(\DOMNode $node)
    {
        $item = $this->getDenormalizer($node)->denormalize($node);
        if ($item instanceof FilesystemItem) {
            $item = new WhiteListItem(
                $item->getType(),
                $item->getValue(),
                $item->getAttributeList()
            );
        }

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($item)
    {
        try {
            $this->getNormalizer($item);
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization(\DomNode $node)
    {
        try {
            $this->getDenormalizer($node);
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
